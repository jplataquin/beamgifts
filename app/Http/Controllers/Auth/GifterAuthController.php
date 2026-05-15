<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Gifter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class GifterAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:gifters'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'terms' => ['accepted'],
        ], [
            'terms.accepted' => 'You must agree to the Terms of Service and Privacy Policy to continue.',
        ]);

        $gifter = Gifter::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($gifter);

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function profile()
    {
        $gifter = Auth::user();
        // Load the 5 most recent orders for the activity section
        $recentOrders = \App\Models\Order::where('gifter_id', $gifter->id)
            ->with('items')
            ->latest()
            ->take(5)
            ->get();
            
        return view('auth.profile', compact('gifter', 'recentOrders'));
    }

    public function updateProfile(Request $request)
    {
        $gifter = Auth::user();

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:gifters,email,' . $gifter->id],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $gifter->first_name = $request->first_name;
        $gifter->last_name = $request->last_name;
        $gifter->email = $request->email;

        if ($request->filled('password')) {
            $gifter->password = Hash::make($request->password);
        }

        $gifter->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
