<?php

namespace App\Http\Controllers;

use App\Models\Call;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = request()->user();
        $calls = Call::query();

        if ($user->role === 'customer') {
            $calls->where('user_id', $user->id);
        } elseif ($user->role === 'agent') {
            $calls->whereHas('agent', fn ($query) => $query->where('user_id', $user->id));
        }

        return view('dashboard', [
            'totalCalls' => (clone $calls)->count(),
            'submittedCalls' => (clone $calls)->where('status', 'submitted')->count(),
            'acceptedCalls' => (clone $calls)->where('status', 'accepted')->count(),
            'resolvedCalls' => (clone $calls)->where('status', 'resolved')->count(),
        ]);
    }
}
