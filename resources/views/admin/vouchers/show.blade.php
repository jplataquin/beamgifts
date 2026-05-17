@extends('layouts.app')

@section('title', 'Voucher Details')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0">
                <div class="card-body p-0">
                    <h5 class="fw-bold text-primary mb-4">Admin Menu</h5>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action rounded-pill mb-1">Dashboard</a>
                        <a href="{{ route('admin.partners.index') }}" class="list-group-item list-group-item-action rounded-pill mb-1">Partners</a>
                        <a href="{{ route('admin.cities.index') }}" class="list-group-item list-group-item-action rounded-pill mb-1">Cities</a>
                        <a href="{{ route('admin.categories.index') }}" class="list-group-item list-group-item-action rounded-pill mb-1">Categories</a>
                        <a href="{{ route('admin.products.index') }}" class="list-group-item list-group-item-action rounded-pill mb-1">Products</a>
                        <a href="{{ route('admin.orders.index') }}" class="list-group-item list-group-item-action rounded-pill mb-1 border-0">Orders</a>
                        <a href="{{ route('admin.vouchers.index') }}" class="list-group-item list-group-item-action active rounded-pill mb-1 border-0">Vouchers</a>
                        <a href="{{ route('admin.settings.index') }}" class="list-group-item list-group-item-action rounded-pill mb-1 border-0">Settings</a>
                        <form action="{{ route('admin.logout') }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100 rounded-pill">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('admin.vouchers.index') }}" class="btn btn-light rounded-pill me-3 px-4">
                    <i class="bi bi-arrow-left me-2"></i>Back to Vouchers
                </a>
                <div>
                    <h1 class="h3 fw-bold mb-0 text-primary">Voucher Details</h1>
                    <p class="text-muted mb-0">ID: <strong>#{{ str_pad($voucher->id, 6, '0', STR_PAD_LEFT) }}</strong></p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-8">
                    <!-- Product Info -->
                    <div class="card shadow-sm border-0 rounded-4 p-4 mb-4">
                        <h5 class="fw-bold mb-4">Product Details</h5>
                        <div class="d-flex">
                            @if($voucher->product && !empty($voucher->product->images))
                                <img src="{{ Storage::url($voucher->product->images[0]) }}" class="rounded me-4" style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded me-4 d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                    <span class="text-muted small">No Image</span>
                                </div>
                            @endif
                            <div>
                                <h4 class="fw-bold mb-1">{{ $voucher->product->name ?? 'Unknown Product' }}</h4>
                                <div class="text-muted mb-2">{{ $voucher->product->store->name ?? 'Unknown Store' }}</div>
                                <div class="fw-bold text-primary h5 mb-0">₱{{ number_format($voucher->price ?? $voucher->product->price, 2) }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Personalization -->
                    <div class="card shadow-sm border-0 rounded-4 p-4 mb-4">
                        <h5 class="fw-bold mb-3">Gift Personalization</h5>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="text-muted small fw-bold text-uppercase mb-1">Personal Message</label>
                                    @if($voucher->personal_message)
                                        <p class="fst-italic border-start border-3 border-primary ps-3 mb-0">{{ $voucher->personal_message }}</p>
                                    @else
                                        <p class="text-muted fst-italic mb-0">None provided</p>
                                    @endif
                                </div>
                                <div>
                                    <label class="text-muted small fw-bold text-uppercase mb-1">Closing Remark</label>
                                    <p class="mb-0">{{ $voucher->closing_remark ?? 'None provided' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small fw-bold text-uppercase mb-1">Custom Photo</label>
                                @if($voucher->custom_photo)
                                    <img src="{{ Storage::url($voucher->custom_photo) }}" class="img-fluid rounded shadow-sm mt-1" alt="Custom Photo">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center py-4 mt-1">
                                        <span class="text-muted small">No Photo</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Redemption Details (If Claimed) -->
                    @if($voucher->status === 'claimed')
                        <div class="card shadow-sm border-0 rounded-4 p-4">
                            <h5 class="fw-bold mb-3 text-secondary">Redemption Details</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small fw-bold text-uppercase mb-1">Claimed By (Customer)</label>
                                    <p class="fw-bold mb-0">{{ $voucher->claimed_by ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small fw-bold text-uppercase mb-1">Claimed At</label>
                                    <p class="mb-0">{{ $voucher->claimed_at ? $voucher->claimed_at->format('M d, Y h:i A') : 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small fw-bold text-uppercase mb-1">Processed By (Staff)</label>
                                    <p class="mb-0">{{ $voucher->claimedByUser ? $voucher->claimedByUser->name : 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small fw-bold text-uppercase mb-1">Branch</label>
                                    <p class="mb-0">{{ $voucher->claimedBranch ? $voucher->claimedBranch->name : 'N/A' }}</p>
                                </div>
                                @if($voucher->remarks)
                                    <div class="col-12 mt-2">
                                        <label class="text-muted small fw-bold text-uppercase mb-1">Staff Remarks</label>
                                        <p class="bg-light p-3 rounded mb-0">{{ $voucher->remarks }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-md-4">
                    <!-- Status -->
                    <div class="card shadow-sm border-0 rounded-4 p-4 mb-4">
                        <h5 class="fw-bold mb-3">Status Overview</h5>
                        <div class="mb-3">
                            @if($voucher->status === 'active')
                                <span class="badge bg-success rounded-pill px-3 py-2 w-100 text-start fs-6"><i class="bi bi-check-circle me-2"></i>Active</span>
                            @elseif($voucher->status === 'claimed')
                                <span class="badge bg-secondary rounded-pill px-3 py-2 w-100 text-start fs-6"><i class="bi bi-gift me-2"></i>Claimed</span>
                            @elseif($voucher->status === 'expired')
                                <span class="badge bg-danger rounded-pill px-3 py-2 w-100 text-start fs-6"><i class="bi bi-x-circle me-2"></i>Expired</span>
                            @else
                                <span class="badge bg-dark rounded-pill px-3 py-2 w-100 text-start fs-6">{{ ucfirst($voucher->status) }}</span>
                            @endif
                        </div>
                        <ul class="list-unstyled mb-0 small text-muted">
                            <li class="mb-2"><strong>Unique Token:</strong><br><span class="text-break">{{ $voucher->unique_token }}</span></li>
                            <li class="mb-2"><strong>Purchased:</strong> {{ $voucher->created_at->format('M d, Y h:i A') }}</li>
                            <li><strong>Expires:</strong> {{ $voucher->expires_at ? $voucher->expires_at->format('M d, Y h:i A') : 'N/A' }}</li>
                        </ul>
                    </div>

                    <!-- Gifter & Order Info -->
                    <div class="card shadow-sm border-0 rounded-4 p-4 mb-4">
                        <h5 class="fw-bold mb-3">Order Information</h5>
                        <div class="mb-3">
                            <label class="text-muted small fw-bold text-uppercase mb-1">Associated Order</label>
                            <div>
                                <a href="{{ route('admin.orders.show', $voucher->order_id) }}" class="fw-bold text-decoration-none">
                                    Order #{{ str_pad($voucher->order_id, 6, '0', STR_PAD_LEFT) }}
                                </a>
                            </div>
                        </div>
                        <div>
                            <label class="text-muted small fw-bold text-uppercase mb-1">Purchased By (Gifter)</label>
                            @if($voucher->order && $voucher->order->gifter)
                                <div class="fw-bold">{{ $voucher->order->gifter->name }}</div>
                                <div>{{ $voucher->order->gifter->email }}</div>
                            @else
                                <div class="fst-italic">Guest / Unknown</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection