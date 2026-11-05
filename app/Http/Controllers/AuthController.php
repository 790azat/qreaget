<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $cred = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($cred, $request->boolean('remember'))) {
            return back()->withErrors(['email' => __('site.auth.failed')])->onlyInput('email');
        }

        $user = $request->user();
        if ($user->is_blocked) {
            Auth::logout();

            return back()->withErrors(['email' => __('site.auth.blocked')])->onlyInput('email');
        }

        $request->session()->regenerate();
        $user->forceFill(['last_login_at' => now()])->save();
        ActivityLog::record('login');

        return redirect()->intended($user->isStaff() ? route('admin.dashboard') : route('account.index'));
    }

    public function showRegister()
    {
        abort_unless(setting('registration_enabled') === '1', 404);

        return view('auth.register');
    }

    public function register(Request $request)
    {
        abort_unless(setting('registration_enabled') === '1', 404);

        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:150|unique:users,email',
            'phone' => 'required|string|max:40',
            'password' => ['required', 'confirmed', Password::min(8)],
            'agree' => 'accepted',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'phone' => $data['phone'],
            'password' => $data['password'],
            'role' => 'client',
        ]);

        Auth::login($user, true);
        $request->session()->regenerate();
        ActivityLog::record('register', $user->email);

        try {
            app(\App\Services\Telegram::class)->send('👤 <b>New client registered</b>: '
                .e($user->name).' · '.e($user->email).' · '.e($user->phone));
        } catch (\Throwable) {
        }

        return redirect()->route('account.index')->with('success', __('site.auth.welcome'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
