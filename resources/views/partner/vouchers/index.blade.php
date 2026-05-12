@extends('layouts.app')

@section('title', 'All Purchased Vouchers')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 h-100">
                <div class="card-body p-0">
                    <h5 class="fw-bold text-primary mb-4">Partner Menu</h5>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('partner.dashboard') }}" class="list-group-item list-group-item-action rounded-pill mb-1 border-0">Dashboard</a>
                        <a href="{{ route('partner.store.show') }}" class="list-group-item list-group-item-action rounded-pill mb-1 border-0">My Store</a>
                        <a href="{{ route('partner.branches.index') }}" class="list-group-item list-group-item-action rounded-pill mb-1 border-0">Manage Branches</a>
                        <a href="{{ route('partner.products.index') }}" class="list-group-item list-group-item-action rounded-pill mb-1 border-0">Products</a>
                        <a href="{{ route('partner.vouchers.index') }}" class="list-group-item list-group-item-action active rounded-pill mb-1 border-0">Vouchers</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 fw-bold mb-0 text-primary">Gift Vouchers</h1>
                <a href="{{ route('partner.vouchers.scan') }}" class="btn btn-primary rounded-pill px-4"><i class="bi bi-qr-code-scan me-2"></i>Scan to Redeem</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success rounded-pill px-4 mb-4">{{ session('success') }}</div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Product</th>
                                    <th>Gifter</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Purchased</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vouchers as $voucher)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold">{{ $voucher->product->name }}</div>
                                        </td>
                                        <td>{{ $voucher->order->gifter->name }}</td>
                                        <td class="fw-bold text-primary">₱{{ number_format($voucher->price ?? $voucher->product->price, 2) }}</td>
                                        <td>
                                            @if($voucher->status === 'active')
                                                <span class="badge bg-success rounded-pill">Active</span>
                                            @elseif($voucher->status === 'claimed')
                                                <span class="badge bg-secondary rounded-pill">Claimed</span>
                                            @elseif($voucher->status === 'expired')
                                                <span class="badge bg-danger rounded-pill">Expired</span>
                                            @endif
                                        </td>
                                        <td class="small text-muted">{{ $voucher->created_at->format('M d, Y') }}</td>
                                        <td class="text-end pe-4">
                                            @if($voucher->status === 'active')
                                                <a href="{{ route('partner.vouchers.scan.result', $voucher->unique_token) }}" class="btn btn-sm btn-outline-primary rounded-pill">View/Claim</a>
                                            @else
                                                <button disabled class="btn btn-sm btn-light rounded-pill">Claimed</button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">No vouchers found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                {{ $vouchers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
