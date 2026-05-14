@extends('layouts.app')

@section('title', 'Voucher Details')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center text-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5">
                <div class="card-body">
                    @if(isset($error))
                        <div class="mb-4">
                            <i class="bi bi-x-circle-fill display-1 text-danger"></i>
                        </div>
                        <h1 class="h3 fw-bold mb-3">Unauthorized</h1>
                        <p class="text-muted mb-5 lead">{{ $error }}</p>
                        <a href="{{ route('partner.vouchers.scan') }}" class="btn btn-primary rounded-pill px-5 py-3 fw-bold">Try Another Scan</a>
                    @else
                        <!-- Status Header -->
                        <div class="mb-4">
                            @if($voucher->status === 'active')
                                <i class="bi bi-check-circle-fill display-1 text-success"></i>
                                <h1 class="h3 fw-bold mb-1 text-success">Valid Voucher</h1>
                                <p class="text-muted mb-0">Ready to be redeemed</p>
                            @elseif($voucher->status === 'claimed')
                                <div class="bg-danger bg-opacity-10 rounded-4 p-4 mb-4 border border-danger border-2 animate-pulse-red">
                                    <i class="bi bi-exclamation-octagon-fill display-1 text-danger d-block mb-3"></i>
                                    <h1 class="h2 fw-bold mb-1 text-danger text-uppercase">Already Redeemed</h1>
                                    <p class="text-danger fw-bold mb-0">This voucher was previously used and is no longer valid.</p>
                                </div>
                                <style>
                                    @keyframes pulse-red {
                                        0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4); }
                                        70% { box-shadow: 0 0 0 15px rgba(220, 53, 69, 0); }
                                        100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
                                    }
                                    .animate-pulse-red {
                                        animation: pulse-red 2s infinite;
                                    }
                                </style>
                            @elseif($voucher->status === 'expired')
                                <i class="bi bi-exclamation-triangle-fill display-1 text-warning"></i>
                                <h1 class="h3 fw-bold mb-1 text-warning">Expired</h1>
                                <p class="text-muted mb-0">This voucher is no longer valid</p>
                            @endif
                        </div>

                        <!-- Product Photo -->
                        @if(!empty($voucher->product->images))
                            <div class="mb-4">
                                <img src="{{ Storage::url($voucher->product->images[0]) }}" class="rounded-4 shadow-sm" style="height: 150px; width: 150px; object-fit: cover;">
                            </div>
                        @endif

                        <hr class="my-4">
                        
                        <!-- Core Details -->
                        <div class="text-start mb-4">
                            <div class="row mb-2 border-bottom pb-2">
                                <div class="col-5 text-muted small">Voucher ID</div>
                                <div class="col-7 fw-bold">#{{ str_pad($voucher->id, 6, '0', STR_PAD_LEFT) }}</div>
                            </div>
                            <div class="row mb-2 border-bottom pb-2">
                                <div class="col-5 text-muted small">Validation Code</div>
                                <div class="col-7 fw-bold font-monospace text-primary">{{ $voucher->validation_code }}</div>
                            </div>
                            <div class="row mb-2 border-bottom pb-2">
                                <div class="col-5 text-muted small">Product</div>
                                <div class="col-7 fw-bold">{{ $voucher->product->name }}</div>
                            </div>
                            <div class="row mb-2 border-bottom pb-2">
                                <div class="col-5 text-muted small">Gifter</div>
                                <div class="col-7 fw-bold">{{ $voucher->order->gifter->name }}</div>
                            </div>
                            <div class="row mb-2 border-bottom pb-2">
                                <div class="col-5 text-muted small">Voucher Price</div>
                                <div class="col-7 fw-bold text-primary">₱{{ number_format($voucher->price ?? $voucher->product->price, 2) }}</div>
                            </div>
                            <div class="row mb-2 border-bottom pb-2">
                                <div class="col-5 text-muted small">Date Bought</div>
                                <div class="col-7 fw-bold">{{ $voucher->order->created_at->format('M d, Y') }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 text-muted small">Valid Until</div>
                                <div class="col-7 fw-bold">{{ $voucher->expires_at->format('M d, Y') }}</div>
                            </div>
                        </div>

                        <!-- Redemption Details (If Claimed) -->
                        @if($voucher->status === 'claimed')
                            <div class="bg-light p-4 rounded-4 text-start mb-4">
                                <h5 class="h6 fw-bold text-uppercase small text-muted mb-3 border-bottom pb-2">Redemption Info</h5>
                                <div class="row mb-2">
                                    <div class="col-5 text-muted small">Redeemed At</div>
                                    <div class="col-7 fw-bold small">{{ $voucher->claimed_at->format('M d, Y h:i A') }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-5 text-muted small">Claimed By</div>
                                    <div class="col-7 fw-bold text-success">{{ $voucher->claimed_by ?? 'N/A' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-5 text-muted">Processed By:</div>
                                    <div class="col-7 fw-bold text-success">{{ $voucher->claimedByUser ? $voucher->claimedByUser->name : 'N/A' }}</div>
                                </div>
                                @if($voucher->remarks)
                                    <div class="mt-3">
                                        <div class="text-muted small fw-bold mb-1">Remarks</div>
                                        <div class="p-2 bg-white rounded border small">{{ $voucher->remarks }}</div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <a href="{{ route('partner.vouchers.index') }}" class="btn btn-light rounded-pill py-3">Back to List</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
