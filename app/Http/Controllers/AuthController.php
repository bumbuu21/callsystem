<?php

namespace App\Http\Controllers;

use App\Models\SystemLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function createLogin(Request $request): View
    {
        return view('auth.login', ['rememberedLogin' => $request->cookie('remembered_login')]);
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::query()
            ->where('email', $credentials['login'])
            ->orWhere('username', $credentials['login'])
            ->first();

        if (! $user || $user->status !== 'active' || ! Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['login' => 'Нэвтрэх нэр эсвэл нууц үг буруу байна.'])->onlyInput('login');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        if ($request->boolean('remember')) {
            cookie()->queue(cookie('remembered_login', $user->username, 60 * 24 * 30));
        } else {
            cookie()->queue(cookie()->forget('remembered_login'));
        }

        SystemLog::create(['user_id' => $user->id, 'action' => 'Системд нэвтэрсэн', 'ip_address' => $request->ip(), 'last_activity' => now()->timestamp]);

        return redirect()->intended(route('dashboard'));
    }

    public function createRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'username' => ['required', 'string', 'min:3', 'max:50', 'alpha_dash', 'unique:users,username'],
            'password' => ['required', 'confirmed', 'min:8'],
            'aimag_name' => ['nullable', 'string', 'max:80'],
            'soum_name' => ['nullable', 'string', 'max:80'],
        ]);

        $user = User::create([
            ...$data,
            'role' => 'customer',
            'status' => 'active',
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        SystemLog::create(['user_id' => $user->id, 'action' => 'Шинээр бүртгүүлсэн', 'ip_address' => $request->ip(), 'last_activity' => now()->timestamp]);

        return redirect()->route('dashboard')->with('success', 'Бүртгэл амжилттай үүслээ.');
    }

    public function logout(Request $request): RedirectResponse
    {
        $user = $request->user();
        if ($user) {
            SystemLog::create(['user_id' => $user->id, 'action' => 'Системээс гарсан', 'ip_address' => $request->ip(), 'last_activity' => now()->timestamp]);
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
