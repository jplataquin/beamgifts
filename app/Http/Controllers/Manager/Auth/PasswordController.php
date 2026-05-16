<?php

namespace App\Http\Controllers\Manager\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function edit()
    {
        return view('manager.auth.change-password');
    }

    public function update(Request $request)
    {
        $manager = Auth::guard('partner')->user();

        $rules = [
            'password' => ['required', 'confirmed', Password::defaults()],
        ];

        if (!$manager->must_change_password) {
            $rules['current_password'] = ['required', 'current_password:partner'];
        }

        $request->validate($rules);

        $manager->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        return redirect()->route('manager.vouchers.transactions')->with('success', 'Password updated successfully. You can now use the application.');
    }
}
