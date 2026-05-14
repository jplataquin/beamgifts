<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request, Voucher $voucher)
    {
        // Authorization: Ensure the voucher belongs to the authenticated gifter
        $voucher->load('order');
        if ($voucher->order->gifter_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Ensure the voucher is claimed and not already reviewed
        if (!$voucher->claimed_at) {
            return back()->with('error', 'You can only review a product after the gift has been claimed.');
        }

        if ($voucher->review) {
            return back()->with('error', 'You have already reviewed this gift.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'product_id' => $voucher->product_id,
            'gifter_id' => Auth::id(),
            'voucher_id' => $voucher->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('vouchers.manage', $voucher)->with('success', 'Thank you for your review!');
    }
}
