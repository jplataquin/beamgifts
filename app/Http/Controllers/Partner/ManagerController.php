<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ManagerController extends Controller
{
    public function index()
    {
        $partner = Auth::guard('partner')->user();
        $managers = Partner::where('store_id', $partner->store->id)
            ->where('role', 'manager')
            ->with('branch')
            ->get();
        return view('partner.managers.index', compact('managers'));
    }

    public function show(Partner $manager)
    {
        $partner = Auth::guard('partner')->user();
        if ($manager->store_id !== $partner->store->id || !$manager->isManager()) abort(403);
        
        return view('partner.managers.show', compact('manager'));
    }

    public function create()
    {
        $partner = Auth::guard('partner')->user();
        $branches = Branch::where('store_id', $partner->store->id)->get();
        return view('partner.managers.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $partner = Auth::guard('partner')->user();
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:partners,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Security: Ensure branch belongs to the partner's store
        $branch = Branch::where('id', $request->branch_id)
                        ->where('store_id', $partner->store->id)
                        ->firstOrFail();

        Partner::create([
            'role' => 'manager',
            'store_id' => $partner->store->id,
            'branch_id' => $branch->id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('partner.managers.index')->with('success', 'Manager created successfully.');
    }

    public function edit(Partner $manager)
    {
        $partner = Auth::guard('partner')->user();
        if ($manager->store_id !== $partner->store->id || !$manager->isManager()) abort(403);
        
        $branches = Branch::where('store_id', $partner->store->id)->get();
        return view('partner.managers.edit', compact('manager', 'branches'));
    }

    public function update(Request $request, Partner $manager)
    {
        $partner = Auth::guard('partner')->user();
        if ($manager->store_id !== $partner->store->id || !$manager->isManager()) abort(403);

        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:partners,email,' . $manager->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Security: Ensure branch belongs to the partner's store
        $branch = Branch::where('id', $request->branch_id)
                        ->where('store_id', $partner->store->id)
                        ->firstOrFail();

        $data = [
            'branch_id' => $branch->id,
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
            $data['must_change_password'] = true;
        }

        $manager->update($data);

        return redirect()->route('partner.managers.index')->with('success', 'Manager updated successfully.');
    }

    public function destroy(Partner $manager)
    {
        $partner = Auth::guard('partner')->user();
        if ($manager->store_id !== $partner->store->id || !$manager->isManager()) abort(403);

        $manager->delete();
        return redirect()->route('partner.managers.index')->with('success', 'Manager deleted.');
    }
}
