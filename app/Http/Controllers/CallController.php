<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Call;
use App\Models\SystemLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CallController extends Controller
{
    public function index(Request $request): View
    {
        $calls = Call::query()->with(['user', 'agent'])->latest('requested_at');

        if ($request->user()->role === 'customer') {
            $calls->where('user_id', $request->user()->id);
        }

        if ($request->user()->role === 'agent') {
            $calls->whereHas('agent', fn ($query) => $query->where('user_id', $request->user()->id));
        }

        return view('calls.index', [
            'calls' => $calls->paginate(20),
            'agents' => $request->user()->role === 'operator' ? Agent::query()->orderBy('name')->get() : collect(),
        ]);
    }

    public function create(Request $request): View
    {
        abort_unless($request->user()->role === 'customer', 403);

        return view('calls.create');
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->role === 'customer', 403);

        $data = $request->validate([
            'call_type' => ['required', 'in:network,program,hardware,other'],
            'call_from' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'photo' => ['nullable', 'image', 'max:5120'],
        ]);

        $call = Call::create([
            'caller_name' => $request->user()->name,
            'caller_phone' => $request->user()->phone,
            'call_type' => $data['call_type'],
            'call_from' => $data['call_from'],
            'description' => $data['description'],
            'user_id' => $request->user()->id,
            'api_username' => $request->user()->username,
            'photo_path' => isset($data['photo']) ? $data['photo']->store('call-photos', 'public') : null,
        ]);

        SystemLog::create([
            'user_id' => $request->user()->id,
            'action' => "Дуудлага #{$call->id} үүсгэсэн",
            'ip_address' => $request->ip(),
            'last_activity' => now()->timestamp,
        ]);

        return redirect()->route('calls.show', $call)->with('success', 'Таны дуудлага амжилттай илгээгдлээ.');
    }

    public function show(Request $request, Call $call): View
    {
        $user = $request->user();
        $isOwner = $user->role === 'customer' && $call->user_id === $user->id;
        $isAssignedAgent = $user->role === 'agent' && $call->agent?->user_id === $user->id;
        $isStaff = in_array($user->role, ['admin', 'operator'], true);

        abort_unless($isOwner || $isAssignedAgent || $isStaff, 403);

        return view('calls.show', [
            'call' => $call->load(['user', 'agent']),
            'agents' => $user->role === 'operator' ? Agent::query()->orderBy('name')->get() : collect(),
        ]);
    }

    public function assign(Request $request, Call $call): RedirectResponse
    {
        abort_unless($request->user()->role === 'operator', 403);
        abort_unless($call->status === 'submitted', 422, 'Энэ дуудлага хуваарилагдсан байна.');

        $data = $request->validate(['agent_id' => ['required', 'exists:agents,id']]);
        $call->update([
            'agent_id' => $data['agent_id'],
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        SystemLog::create([
            'user_id' => $request->user()->id,
            'action' => "Дуудлага #{$call->id}-г инженер рүү хуваарилсан",
            'ip_address' => $request->ip(),
            'last_activity' => now()->timestamp,
        ]);

        return back()->with('success', 'Дуудлагыг инженер рүү амжилттай хуваариллаа.');
    }

    public function resolve(Request $request, Call $call): RedirectResponse
    {
        $isAssignedAgent = $request->user()->role === 'agent' && $call->agent?->user_id === $request->user()->id;
        abort_unless($isAssignedAgent, 403);
        abort_unless($call->status === 'accepted', 422, 'Зөвхөн хүлээн авсан дуудлагыг шийдвэрлэж болно.');

        $data = $request->validate(['agent_description' => ['required', 'string', 'max:5000']]);
        $call->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'agent_description' => $data['agent_description'],
        ]);

        SystemLog::create([
            'user_id' => $request->user()->id,
            'action' => "Дуудлага #{$call->id}-г шийдвэрлэсэн",
            'ip_address' => $request->ip(),
            'last_activity' => now()->timestamp,
        ]);

        return back()->with('success', 'Дуудлагыг шийдвэрлэсэн төлөвт шилжүүллээ.');
    }

    public function comment(Request $request, Call $call): RedirectResponse
    {
        $isOwner = $request->user()->role === 'customer' && $call->user_id === $request->user()->id;
        abort_unless($isOwner, 403);
        abort_unless($call->status === 'resolved', 422, 'Шийдвэрлэсэн дуудлагад сэтгэгдэл үлдээнэ.');

        $data = $request->validate(['client_comment' => ['required', 'string', 'max:2000']]);
        $call->update(['client_comment' => $data['client_comment']]);

        SystemLog::create([
            'user_id' => $request->user()->id,
            'action' => "Дуудлага #{$call->id}-д сэтгэгдэл үлдээсэн",
            'ip_address' => $request->ip(),
            'last_activity' => now()->timestamp,
        ]);

        return back()->with('success', 'Сэтгэгдлийг амжилттай хадгаллаа.');
    }
}
