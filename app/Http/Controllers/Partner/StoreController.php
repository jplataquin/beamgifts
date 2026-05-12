<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    /**
     * Display the partner's store.
     */
    public function show()
    {
        $store = Auth::guard('partner')->user()->store()->withCount(['branches', 'products'])->first();
        
        if (!$store) {
            return redirect()->route('partner.dashboard')->with('error', 'Store not found. Please contact admin.');
        }

        return view('partner.stores.show', compact('store'));
    }

    /**
     * Show the form for editing the store.
     */
    public function edit()
    {
        $store = Auth::guard('partner')->user()->store;
        
        if (!$store) {
            abort(404);
        }

        return view('partner.stores.edit', compact('store'));
    }

    /**
     * Update the store in storage.
     */
    public function update(Request $request)
    {
        $store = Auth::guard('partner')->user()->store;
        
        if (!$store) {
            abort(404);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        $store->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $store->update(['logo' => $path]);
        }

        return redirect()->route('partner.store.show')->with('success', 'Store updated successfully.');
    }
}
