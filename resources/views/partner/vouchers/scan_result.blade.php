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
                                <i class="bi bi-info-circle-fill display-1 text-secondary"></i>
                                <h1 class="h3 fw-bold mb-1">Redeemed</h1>
                                <p class="text-muted mb-0">This voucher has been used</p>
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
                            @if($voucher->status === 'active')
                                <button type="button" class="btn btn-primary rounded-pill py-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#claimModal">
                                    <i class="bi bi-gift me-2"></i> Redeem Voucher
                                </button>
                            @endif
                            <a href="{{ route('partner.vouchers.index') }}" class="btn btn-light rounded-pill py-3">Back to List</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if(!isset($error) && isset($voucher) && $voucher->status === 'active')
    <!-- Claim Modal -->
    <div class="modal fade" id="claimModal" tabindex="-1" aria-labelledby="claimModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="modal-title fw-bold text-primary" id="claimModalLabel">Confirm Redemption</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('partner.vouchers.claim', $voucher) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body text-start px-4 pt-3">
                        <div class="alert alert-warning small border-0 rounded-3 mb-4">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Important:</strong> This action is non-reversable.
                        </div>

                        <div class="mb-3">
                            <label for="claimed_by" class="form-label small fw-bold text-muted">Claimed By (Claimant Name)</label>
                            <input type="text" name="claimed_by" id="claimed_by" class="form-control rounded-pill border-primary border-opacity-25" placeholder="Enter name of person claiming" required>
                        </div>

                        <div class="mb-0">
                            <label for="remarks" class="form-label small fw-bold text-muted">Remarks (Optional)</label>
                            <textarea name="remarks" id="remarks" class="form-control rounded-4 border-primary border-opacity-25" rows="3" placeholder="Add any internal notes here..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-4 px-4">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Confirm & Claim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection
