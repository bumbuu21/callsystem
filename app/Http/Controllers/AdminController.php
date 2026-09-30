<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Call;
use App\Models\SystemLog;
use App\Models\User;
use App\Support\Labels;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    public function calls(Request $request): View
    {
        $this->ensureAdmin($request);
        $filters = $this->callFilters($request);
        $query = $this->filteredCalls($filters);

        return view('admin.calls', [
            'calls' => $query->paginate(20)->withQueryString(),
            'agents' => Agent::query()->orderBy('name')->get(),
            'filters' => $filters,
            'total' => Call::count(),
            'submitted' => Call::where('status', 'submitted')->count(),
            'accepted' => Call::where('status', 'accepted')->count(),
            'resolved' => Call::where('status', 'resolved')->count(),
            'byType' => Call::query()->selectRaw('call_type, COUNT(*) as total')->groupBy('call_type')->pluck('total', 'call_type'),
        ]);
    }

    public function exportCalls(Request $request): StreamedResponse
    {
        $this->ensureAdmin($request);
        $calls = $this->filteredCalls($this->callFilters($request))->get();

        return response()->streamDownload(function () use ($calls): void {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, ['Дуудлага', 'Илгээгч', 'Төрөл', 'Байршил', 'Инженер', 'Төлөв', 'Илгээсэн огноо']);

            foreach ($calls as $call) {
                fputcsv($output, [
                    $call->id, $call->caller_name, Labels::type($call->call_type), $call->call_from,
                    $call->agent?->name ?? 'Хуваарилаагүй', Labels::status($call->status),
                    $call->requested_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($output);
        }, 'duudlagiin-tailan.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function pdfCalls(Request $request)
    {
        $this->ensureAdmin($request);

        return Pdf::loadView('admin.report-pdf', ['calls' => $this->filteredCalls($this->callFilters($request))->get()])->download('duudlagiin-tailan.pdf');
    }

    public function excelCalls(Request $request): StreamedResponse
    {
        $this->ensureAdmin($request);
        $calls = $this->filteredCalls($this->callFilters($request))->get();

        return response()->streamDownload(function () use ($calls): void {
            echo '<table><tr><th>Дуудлага</th><th>Илгээгч</th><th>Төрөл</th><th>Байршил</th><th>Инженер</th><th>Төлөв</th></tr>';
            foreach ($calls as $call) {
                echo '<tr><td>'.$call->id.'</td><td>'.e($call->caller_name).'</td><td>'.e(Labels::type($call->call_type)).'</td><td>'.e($call->call_from).'</td><td>'.e($call->agent?->name ?? 'Хуваарилаагүй').'</td><td>'.e(Labels::status($call->status)).'</td></tr>';
            }
            echo '</table>';
        }, 'duudlagiin-tailan.xls', ['Content-Type' => 'application/vnd.ms-excel; charset=UTF-8']);
    }

    public function users(Request $request): View
    {
        $this->ensureAdmin($request);

        return view('admin.users', ['users' => User::query()->orderBy('role')->orderBy('name')->paginate(20)]);
    }

    public function report(Request $request): View
    {
        $this->ensureAdmin($request);

        return view('admin.report', ['calls' => $this->filteredCalls($this->callFilters($request))->get()]);
    }

    public function createUser(Request $request): View
    {
        $this->ensureAdmin($request);

        return view('admin.create-user');
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $this->ensureAdmin($request);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'username' => ['required', 'alpha_dash', 'min:3', 'max:50', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,operator,agent,customer'],
            'phone' => ['nullable', 'string', 'max:20'],
            'title' => ['nullable', 'string', 'max:100'],
        ]);

        $user = User::create([
            'name' => $data['name'], 'email' => $data['email'], 'username' => $data['username'],
            'password' => Hash::make($data['password']), 'role' => $data['role'],
            'phone' => $data['phone'] ?? null, 'status' => 'active',
        ]);

        if ($user->role === 'agent') {
            Agent::create([
                'user_id' => $user->id, 'name' => $user->name,
                'title' => $data['title'] ?? 'Инженер', 'phone' => $user->phone,
            ]);
        }

        SystemLog::create([
            'user_id' => $request->user()->id,
            'action' => "{$user->username} хэрэглэгчийг үүсгэсэн",
            'ip_address' => $request->ip(),
            'last_activity' => now()->timestamp,
        ]);

        return redirect()->route('admin.users')->with('success', 'Хэрэглэгч амжилттай үүслээ.');
    }

    public function toggleUser(Request $request, User $user): RedirectResponse
    {
        $this->ensureAdmin($request);
        abort_if($user->id === $request->user()->id, 422, 'Өөрийн бүртгэлийг идэвхгүй болгож болохгүй.');

        $user->update(['status' => $user->status === 'active' ? 'inactive' : 'active']);
        SystemLog::create([
            'user_id' => $request->user()->id,
            'action' => "{$user->username} хэрэглэгчийн төлөвийг ".Labels::accountStatus($user->status).' болгосон',
            'ip_address' => $request->ip(),
            'last_activity' => now()->timestamp,
        ]);

        return back()->with('success', 'Хэрэглэгчийн төлөв шинэчлэгдлээ.');
    }

    public function destroyUser(Request $request, User $user): RedirectResponse
    {
        $this->ensureAdmin($request);
        abort_if($user->id === $request->user()->id, 422, 'Өөрийн бүртгэлийг устгаж болохгүй.');

        $username = $user->username;
        Agent::query()->where('user_id', $user->id)->delete();
        $user->delete();
        SystemLog::create([
            'user_id' => $request->user()->id,
            'action' => "{$username} хэрэглэгчийг устгасан",
            'ip_address' => $request->ip(),
            'last_activity' => now()->timestamp,
        ]);

        return back()->with('success', 'Хэрэглэгч устгагдлаа.');
    }

    public function logs(Request $request): View
    {
        $this->ensureAdmin($request);

        return view('admin.logs', ['logs' => SystemLog::query()->with('user')->latest('done_at')->paginate(50)]);
    }

    private function ensureAdmin(Request $request): void
    {
        abort_unless($request->user()->role === 'admin', 403);
    }

    private function callFilters(Request $request): array
    {
        return $request->validate([
            'year' => ['nullable', 'integer', 'min:2020', 'max:2100'],
            'month' => ['nullable', 'integer', 'min:1', 'max:12'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'aimag_name' => ['nullable', 'string', 'max:80'],
            'soum_name' => ['nullable', 'string', 'max:80'],
            'call_from' => ['nullable', 'string', 'max:255'],
            'call_type' => ['nullable', 'in:network,program,hardware,other'],
            'status' => ['nullable', 'in:submitted,accepted,resolved'],
            'agent_id' => ['nullable', 'exists:agents,id'],
            'search' => ['nullable', 'string', 'max:255'],
        ]);
    }

    private function filteredCalls(array $filters)
    {
        $query = Call::query()->with(['user', 'agent'])->latest('requested_at');
        $query->when($filters['year'] ?? null, fn ($q, $year) => $q->whereYear('requested_at', $year));
        $query->when($filters['month'] ?? null, fn ($q, $month) => $q->whereMonth('requested_at', $month));
        $query->when($filters['start_date'] ?? null, fn ($q, $date) => $q->whereDate('requested_at', '>=', $date));
        $query->when($filters['end_date'] ?? null, fn ($q, $date) => $q->whereDate('requested_at', '<=', $date));
        $query->when($filters['aimag_name'] ?? null, fn ($q, $value) => $q->whereHas('user', fn ($u) => $u->where('aimag_name', $value)));
        $query->when($filters['soum_name'] ?? null, fn ($q, $value) => $q->whereHas('user', fn ($u) => $u->where('soum_name', $value)));
        $query->when($filters['call_from'] ?? null, fn ($q, $value) => $q->where('call_from', 'like', "%{$value}%"));
        $query->when($filters['call_type'] ?? null, fn ($q, $type) => $q->where('call_type', $type));
        $query->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status));
        $query->when($filters['agent_id'] ?? null, fn ($q, $agent) => $q->where('agent_id', $agent));
        $query->when($filters['search'] ?? null, function ($q, $search) {
            $q->where(fn ($nested) => $nested->where('caller_name', 'like', "%{$search}%")
                ->orWhere('call_from', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%"));
        });

        return $query;
    }
}
