<?php

namespace App\Http\Controllers\Partner\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function edit()
    {
        return view('partner.auth.change-password');
    }

    public function update(Request $request)
    {
        $partner = Auth::guard('partner')->user();

        $rules = [
            'password' => ['required', 'confirmed', Password::defaults()],
        ];

        if (!$partner->must_change_password) {
            $rules['current_password'] = ['required', 'current_password:partner'];
        }

        $request->validate($rules);

        $partner->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        return redirect()->route('partner.dashboard')->with('success', 'Password updated successfully.');
    }
}
