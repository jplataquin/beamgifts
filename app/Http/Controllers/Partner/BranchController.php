<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    public function index()
    {
        $store = Auth::guard('partner')->user()->store;
        $branches = $store->branches()->with('city')->get();
        return view('partner.branches.index', compact('store', 'branches'));
    }

    public function create()
    {
        $store = Auth::guard('partner')->user()->store;
        $cities = City::where('is_active', true)->get();
        return view('partner.branches.create', compact('store', 'cities'));
    }

    public function store(Request $request)
    {
        $store = Auth::guard('partner')->user()->store;
        
        $validated = $request->validate([
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'map_url' => 'nullable|url|max:2048',
        ]);

        $store->branches()->create($validated);

        return redirect()->route('partner.branches.index')->with('success', 'Branch added successfully.');
    }

    public function edit(Branch $branch)
    {
        $store = Auth::guard('partner')->user()->store;
        if ($branch->store_id !== $store->id) abort(403);

        $cities = City::where('is_active', true)->get();
        return view('partner.branches.edit', compact('store', 'branch', 'cities'));
    }

    public function update(Request $request, Branch $branch)
    {
        $store = Auth::guard('partner')->user()->store;
        if ($branch->store_id !== $store->id) abort(403);

        $validated = $request->validate([
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'map_url' => 'nullable|url|max:2048',
        ]);

        $branch->update($validated);

        return redirect()->route('partner.branches.index')->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        $store = Auth::guard('partner')->user()->store;
        if ($branch->store_id !== $store->id) abort(403);

        $branch->delete();
        return redirect()->route('partner.branches.index')->with('success', 'Branch deleted successfully.');
    }
}
