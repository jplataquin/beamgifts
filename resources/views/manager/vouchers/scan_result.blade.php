@extends('layouts.app')

@section('title', 'Voucher Details')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="h3 fw-bold mb-4 text-primary text-center">Voucher Details</h1>

            @if(isset($error))
                <div class="alert alert-danger rounded-4 p-4 border-0 shadow-sm mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $error }}
                </div>
            @endif

            <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        @if(!empty($voucher->product->images))
                            <img src="{{ Storage::url($voucher->product->images[0]) }}" class="rounded-4 mb-4 shadow-sm" style="width: 150px; height: 150px; object-fit: cover;">
                        @endif
                        <h2 class="h4 fw-bold mb-1">{{ $voucher->product->name }}</h2>
                        <p class="text-muted">Voucher ID: <code>{{ $voucher->unique_token }}</code></p>
                    </div>

                    <div class="bg-light rounded-4 p-4 mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Status:</span>
                            <span class="badge {{ $voucher->status === 'active' ? 'bg-success' : 'bg-secondary' }} rounded-pill px-3">
                                {{ strtoupper($voucher->status) }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Gifter:</span>
                            <span class="fw-bold">{{ $voucher->order->gifter->name }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Price:</span>
                            <span class="fw-bold text-primary">₱{{ number_format($voucher->price ?? $voucher->product->price, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Bought:</span>
                            <span class="fw-bold">{{ $voucher->order->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Expires:</span>
                            <span class="fw-bold">{{ $voucher->expires_at ? $voucher->expires_at->format('M d, Y') : 'N/A' }}</span>
                        </div>
                        @if($voucher->status === 'claimed')
                            <div class="d-flex justify-content-between mt-2 pt-2 border-top">
                                <span class="text-muted">Claimed By:</span>
                                <span class="fw-bold text-success">{{ $voucher->claimed_by ?? 'N/A' }}</span>
                            </div>
                            @if($voucher->remarks)
                                <div class="mt-3 p-3 bg-light rounded-4 small text-start">
                                    <div class="fw-bold text-muted small text-uppercase mb-1" style="font-size: 0.65rem;">Remarks</div>
                                    {{ $voucher->remarks }}
                                </div>
                            @endif
                        @endif
                    </div>

                    @if(!isset($error) && $voucher->status === 'active')
                        <form action="{{ route('manager.vouchers.claim', $voucher) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            
                            <div class="mb-3 text-start">
                                <label for="claimed_by" class="form-label small fw-bold text-muted">Claimed By (Claimant Name)</label>
                                <input type="text" name="claimed_by" id="claimed_by" class="form-control rounded-pill border-primary border-opacity-25" placeholder="Enter name of person claiming" required>
                            </div>

                            <div class="mb-4 text-start">
                                <label for="remarks" class="form-label small fw-bold text-muted">Remarks (Optional)</label>
                                <textarea name="remarks" id="remarks" class="form-control rounded-4 border-primary border-opacity-25" rows="2" placeholder="Internal notes..."></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary rounded-pill py-3 fw-bold shadow-sm" onclick="return confirm('Are you sure?, this action is non-reversable')">
                                    Confirm Redemption
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('manager.vouchers.scan') }}" class="btn btn-light rounded-pill px-4">Back to Scanner</a>
            </div>
        </div>
    </div>
</div>
@endsection