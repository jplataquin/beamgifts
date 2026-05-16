<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    public function scan()
    {
        return view('manager.vouchers.scan');
    }

    public function scanResult($token)
    {
        $manager = Auth::guard('partner')->user();
        $voucher = Voucher::where('unique_token', $token)->with(['product.store', 'order.gifter'])->firstOrFail();

        // Security: Ensure voucher belongs to the manager's store
        if ($voucher->product->store_id !== $manager->store_id) {
            return view('manager.vouchers.scan_result', [
                'error' => 'This voucher belongs to a different store.',
                'voucher' => $voucher
            ]);
        }

        return view('manager.vouchers.scan_result', compact('voucher'));
    }

    public function claim(Request $request, Voucher $voucher)
    {
        $manager = Auth::guard('partner')->user();

        if ($voucher->product->store_id !== $manager->store_id) {
            abort(403);
        }

        if ($voucher->status !== 'active') {
            return back()->with('error', 'Only active vouchers can be claimed.');
        }

        if ($voucher->expires_at && $voucher->expires_at->isPast()) {
            $voucher->update(['status' => 'expired']);
            return back()->with('error', 'This voucher has expired.');
        }

        $request->validate([
            'claimed_by' => 'required|string|max:255',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $voucher->update([
            'status' => 'claimed',
            'claimed_at' => now(),
            'processed_at' => now(),
            'claimed_branch_id' => $manager->branch_id,
            'claimed_by' => $request->claimed_by,
            'remarks' => $request->remarks,
            'claimed_by_user_id' => $manager->id,
            'claimed_by_user_type' => get_class($manager)
        ]);

        return redirect()->route('manager.vouchers.transactions')->with('success', 'Voucher claimed successfully at ' . $manager->branch->name . '!');
    }

    public function transactions(Request $request)
    {
        $manager = Auth::guard('partner')->user();
        $query = Voucher::where('claimed_branch_id', $manager->branch_id)
                           ->with(['product', 'order.gifter', 'claimedByUser']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('claimed_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('claimed_at', '<=', $request->to_date);
        }

        $vouchers = $query->latest('claimed_at')->paginate(20)->withQueryString();

        return view('manager.vouchers.transactions', compact('vouchers'));
    }
}
