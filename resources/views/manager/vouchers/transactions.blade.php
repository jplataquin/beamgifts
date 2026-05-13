@extends('layouts.app')

@section('title', 'Branch Transactions')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <h1 class="h3 fw-bold mb-0 text-primary">Redemption History</h1>
        <p class="text-muted">History of vouchers claimed at <strong>{{ Auth::guard('manager')->user()->branch->name }}</strong>.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-pill px-4 mb-4 border-0 shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Redeemed At</th>
                        <th class="py-3">Product</th>
                        <th class="py-3">Price</th>
                        <th class="py-3">Processed By</th>
                        <th class="py-3">Voucher ID</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vouchers as $voucher)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $voucher->claimed_at->format('M d, Y') }}</div>
                                <div class="small text-muted">{{ $voucher->claimed_at->format('h:i A') }}</div>
                            </td>
                            <td class="fw-bold">{{ $voucher->product->name }}</td>
                            <td class="fw-bold text-primary">₱{{ number_format($voucher->price ?? $voucher->product->price, 2) }}</td>
                            <td>{{ $voucher->claimedByUser ? $voucher->claimedByUser->name : 'N/A' }}</td>
                            <td><code>#{{ str_pad($voucher->id, 6, '0', STR_PAD_LEFT) }}</code></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">No transactions recorded for this branch yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $vouchers->links() }}
    </div>
</div>
@endsection