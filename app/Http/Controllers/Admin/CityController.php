<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::withCount('branches')->latest()->paginate(10);
        return view('admin.cities.index', compact('cities'));
    }

    public function create()
    {
        return view('admin.cities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:cities',
            'is_active' => 'boolean',
        ]);

        City::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.cities.index')->with('success', 'City added successfully.');
    }

    public function edit(City $city)
    {
        return view('admin.cities.edit', compact('city'));
    }

    public function update(Request $request, City $city)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:cities,name,' . $city->id,
            'is_active' => 'boolean',
        ]);

        $city->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.cities.index')->with('success', 'City updated successfully.');
    }

    public function destroy(City $city)
    {
        $city->delete();
        return redirect()->route('admin.cities.index')->with('success', 'City deleted successfully.');
    }
}
