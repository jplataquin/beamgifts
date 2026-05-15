<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\City;

class GifterSettingsController extends Controller
{
    public function edit()
    {
        $gifter = Auth::user();
        $cities = City::orderBy('name')->get();
        return view('auth.settings', compact('gifter', 'cities'));
    }

    public function update(Request $request)
    {
        $gifter = Auth::user();
        
        $request->validate([
            'default_city_id' => ['nullable', 'exists:cities,id'],
        ]);

        $gifter->default_city_id = $request->default_city_id;
        $gifter->save();

        return back()->with('success', 'Settings updated successfully.');
    }

    public function deactivate(Request $request)
    {
        $gifter = Auth::user();

        // Perform any required cleanup here (like soft deletes if added later). 
        // For now, we will permanently delete the user as requested.
        Auth::guard('web')->logout();
        
        $gifter->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Your account has been deactivated.');
    }
}

