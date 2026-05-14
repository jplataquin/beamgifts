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
                    @if($voucher->status === 'claimed')
                        <div class="bg-danger bg-opacity-10 rounded-4 p-4 mb-5 border border-danger border-2 text-center animate-pulse-red">
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
                    @endif

                    <div class="text-center mb-4">
                        @if(!empty($voucher->product->images))
                            <img src="{{ Storage::url($voucher->product->images[0]) }}" class="rounded-4 mb-4 shadow-sm" style="width: 150px; height: 150px; object-fit: cover;">
                        @endif
                        <h2 class="h4 fw-bold mb-1">{{ $voucher->product->name }}</h2>
                        <p class="text-muted fw-bold mb-0">ID: <code>#{{ str_pad($voucher->id, 6, '0', STR_PAD_LEFT) }}</code></p>
                        <p class="text-primary small font-monospace fw-bold mt-1">Validation: {{ $voucher->validation_code }}</p>
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
                            <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                <span class="text-muted">Processed By:</span>
                                <span class="fw-bold text-success">{{ $voucher->claimedByUser ? $voucher->claimedByUser->name : 'N/A' }}</span>
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
                        <div class="d-grid">
                            <button type="button" class="btn btn-primary rounded-pill py-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#claimModal">
                                <i class="bi bi-gift me-2"></i> Confirm Redemption
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('manager.vouchers.scan') }}" class="btn btn-light rounded-pill px-4">Back to Scanner</a>
            </div>
        </div>
    </div>
</div>

@if(!isset($error) && isset($voucher) && $voucher->status === 'active')
    <!-- Claim Modal (Moved to end to avoid backdrop issues) -->
    <div class="modal fade" id="claimModal" tabindex="-1" aria-labelledby="claimModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="modal-title fw-bold text-primary" id="claimModalLabel">Redeem Voucher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('manager.vouchers.claim', $voucher) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body text-start px-4 pt-3">
                        <div class="alert alert-warning small border-0 rounded-3 mb-4">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            This action is non-reversable.
                        </div>

                        <div class="mb-3">
                            <label for="claimed_by" class="form-label small fw-bold text-muted">Claimed By (Claimant Name)</label>
                            <input type="text" name="claimed_by" id="claimed_by" class="form-control rounded-pill border-primary border-opacity-25" placeholder="Enter name of person claiming" required>
                        </div>

                        <div class="mb-2">
                            <label for="remarks" class="form-label small fw-bold text-muted">Remarks (Optional)</label>
                            <textarea name="remarks" id="remarks" class="form-control rounded-4 border-primary border-opacity-25" rows="2" placeholder="Internal notes..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Confirm & Claim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection
