<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::with('store')->latest()->paginate(10);
        return view('admin.partners.index', compact('partners'));
    }

    public function create()
    {
        return view('admin.partners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:partners',
            'phone1' => 'nullable|string|max:20|required_without:phone2',
            'phone2' => 'nullable|string|max:20|required_without:phone1',
            'business_name' => 'required|string|max:255',
            'store_description' => 'nullable|string',
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'phone1.required_without' => 'At least one mobile number is required.',
            'phone2.required_without' => 'At least one mobile number is required.',
        ]);

        try {
            DB::beginTransaction();

            $partner = Partner::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone1' => $validated['phone1'],
                'phone2' => $validated['phone2'],
                'business_name' => $validated['business_name'],
                'password' => Hash::make($validated['password']),
            ]);

            // Automatically create a store for the partner
            $partner->store()->create([
                'name' => $validated['business_name'],
                'slug' => Str::slug($validated['business_name']) . '-' . rand(1000, 9999),
                'description' => $validated['store_description'],
            ]);

            DB::commit();

            return redirect()->route('admin.partners.index')->with('success', 'Partner and store created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create partner account. Please try again.');
        }
    }

    public function edit(Partner $partner)
    {
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:partners,email,' . $partner->id,
            'phone1' => 'nullable|string|max:20|required_without:phone2',
            'phone2' => 'nullable|string|max:20|required_without:phone1',
            'business_name' => 'required|string|max:255',
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ], [
            'phone1.required_without' => 'At least one mobile number is required.',
            'phone2.required_without' => 'At least one mobile number is required.',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone1' => $validated['phone1'],
            'phone2' => $validated['phone2'],
            'business_name' => $validated['business_name'],
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        $partner->update($data);

        return redirect()->route('admin.partners.index')->with('success', 'Partner updated successfully.');
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();
        return redirect()->route('admin.partners.index')->with('success', 'Partner deleted successfully.');
    }

    public function toggleBan(Partner $partner)
    {
        $partner->update(['is_banned' => !$partner->is_banned]);
        $status = $partner->is_banned ? 'banned' : 'unbanned';
        return back()->with('success', "Partner has been {$status}.");
    }
}
