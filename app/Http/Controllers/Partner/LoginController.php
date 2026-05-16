<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('partner.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('partner')->attempt($credentials)) {
            $user = Auth::guard('partner')->user();

            if ($user->is_banned) {
                Auth::guard('partner')->logout();
                return back()->withErrors([
                    'email' => 'Your account has been suspended.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            if ($user->isManager()) {
                return redirect()->intended(route('manager.vouchers.scan'));
            }

            return redirect()->intended(route('partner.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('partner')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('partner.login');
    }
}
