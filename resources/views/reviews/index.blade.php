@extends('layouts.app')

@section('title', 'Review Your Gifts - Beam Gifts')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            @include('partials.account-menu')
        </div>
        <div class="col-md-9">
            <h1 class="h3 fw-bold mb-4 text-primary">Gifts Ready for Review</h1>
            <p class="text-muted mb-5">These gifts have been claimed by your recipients. Share your experience with the products!</p>
            
            @if(session('success'))
                <div class="alert alert-success rounded-pill px-4 mb-4">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger rounded-pill px-4 mb-4">{{ session('error') }}</div>
            @endif

            <div class="row g-4">
                @forelse($vouchers as $voucher)
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100 hover-card overflow-hidden">
                            <div class="row g-0 h-100">
                                <div class="col-4">
                                    @php 
                                        $displayPhoto = $voucher->custom_photo ?: (!empty($voucher->product->images) ? $voucher->product->images[0] : null);
                                    @endphp
                                    @if($displayPhoto)
                                        <img src="{{ Storage::url($displayPhoto) }}" class="img-fluid h-100 w-100" style="object-fit: cover;">
                                    @else
                                        <div class="bg-light h-100 w-100 d-flex align-items-center justify-content-center">
                                            <i class="bi bi-image text-muted fs-2"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-8">
                                    <div class="card-body p-4 d-flex flex-column h-100">
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="badge bg-success rounded-pill px-3">CLAIMED</span>
                                                <small class="text-muted">{{ $voucher->claimed_at->format('M d, Y') }}</small>
                                            </div>
                                            
                                            <h5 class="fw-bold mb-1">{{ $voucher->product->name }}</h5>
                                            <p class="text-muted small mb-0">Store: {{ $voucher->product->store->name }}</p>
                                        </div>

                                        <div class="mt-auto">
                                            <button class="btn btn-primary rounded-pill w-100 py-2 fw-bold" data-bs-toggle="modal" data-bs-target="#reviewModal-{{ $voucher->id }}">
                                                Write a Review
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Review Modal -->
                        <div class="modal fade" id="reviewModal-{{ $voucher->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 rounded-4 shadow">
                                    <form action="{{ route('reviews.store', $voucher) }}" method="POST">
                                        @csrf
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold">Review {{ $voucher->product->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body py-4">
                                            <div class="text-center mb-4">
                                                <p class="text-muted small mb-2">How would you rate this product?</p>
                                                <div class="rating-input d-flex justify-content-center gap-2">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <input type="radio" name="rating" value="{{ $i }}" id="star-{{ $voucher->id }}-{{ $i }}" class="btn-check" required>
                                                        <label for="star-{{ $voucher->id }}-{{ $i }}" class="btn btn-outline-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                                            <i class="bi bi-star-fill"></i>
                                                        </label>
                                                    @endfor
                                                </div>
                                            </div>

                                            <div class="mb-0">
                                                <label class="form-label small fw-bold text-muted text-uppercase">Your Feedback (Optional)</label>
                                                <textarea name="comment" class="form-control border-light bg-light rounded-3" rows="4" placeholder="Tell us what you liked or how we can improve..." maxlength="1000"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Submit Review</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-chat-heart display-1 text-light"></i>
                        </div>
                        <h2 class="h5 fw-bold">No reviews pending</h2>
                        <p class="text-muted mb-4">When your recipients claim their gifts, they will appear here for you to review.</p>
                        <a href="{{ route('my-gifts') }}" class="btn btn-outline-primary rounded-pill px-5 py-2">Back to My Gifts</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    .hover-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .rating-input .btn-check:checked + .btn-outline-warning {
        background-color: var(--bs-warning);
        color: white;
        border-color: var(--bs-warning);
    }
</style>
@endsection
