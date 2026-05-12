@extends('layouts.app')

@section('title', 'Your Digital Gift - Beam Gifts')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <!-- State 1: Wrapped Gift -->
            <div id="gift-wrapped" class="text-center py-5">
                <div class="gift-box-container mb-5">
                    <i class="bi bi-gift-fill text-primary" style="font-size: 8rem;" id="gift-icon"></i>
                </div>
                
                <h1 class="h3 fw-bold mb-3">You've received a gift!</h1>
                <p class="text-muted mb-5">From: <strong>{{ $voucher->order->gifter->name }}</strong></p>

                @if($voucher->personal_message || $voucher->custom_photo)
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-5 mx-auto" style="max-width: 500px; background-color: var(--bs-primary-bg-subtle);">
                        <div class="card-body p-0">
                            @if($voucher->custom_photo)
                                <div class="mb-4">
                                    <img src="{{ Storage::url($voucher->custom_photo) }}" class="rounded-4 w-100 shadow-sm" style="max-height: 350px; object-fit: cover;">
                                </div>
                            @endif

                            @if($voucher->personal_message)
                                <p class="fst-italic fs-5 mb-0 px-3 pb-2" style="color: var(--bs-primary-text-emphasis);">
                                    <i class="bi bi-quote text-primary fs-4"></i>
                                    {{ $voucher->personal_message }}
                                    <i class="bi bi-quote text-primary bi-quote-reverse fs-4"></i>
                                </p>
                            @endif
                        </div>
                    </div>
                @endif

                <button class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-bold shadow-sm" id="unwrap-btn">
                    <i class="bi bi-stars me-2"></i> Unwrap Your Gift
                </button>
            </div>

            <!-- State 2: Unwrapped Voucher (Hidden initially) -->
            <div id="gift-unwrapped" class="d-none">
                <div class="card shadow border-0 overflow-hidden rounded-4 animate-fade-in">
                    
                    <div class="py-4 text-center" style="background-color: var(--bs-primary-bg-subtle); color: var(--bs-primary-text-emphasis);">
                        <h1 class="h4 fw-bold mb-0">Digital Gift Voucher</h1>
                        <p class="small opacity-75 mb-0">Beam Gifts - Share the Joy</p>
                    </div>
                    
                    <div class="card-body p-4 p-md-5 text-center">
                        @if(!empty($voucher->product->images))
                            <div class="mb-4">
                                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mx-auto" style="max-width: 400px;">
                                    <img src="{{ Storage::url($voucher->product->images[0]) }}" class="card-img-top" style="height: 250px; object-fit: cover;">
                                </div>
                            </div>
                        @endif

                        @if($voucher->status === 'claimed')
                            <div class="badge bg-secondary rounded-pill px-3 py-2 mb-4">REDEEMED</div>
                        @elseif($voucher->status === 'expired')
                            <div class="badge bg-danger rounded-pill px-3 py-2 mb-4">EXPIRED</div>
                        @else
                            <div class="badge bg-success rounded-pill px-3 py-2 mb-4">ACTIVE</div>
                        @endif

                        <h2 class="h3 fw-bold mb-1">{{ $voucher->product->name }}</h2>
                        <p class="text-muted mb-4">By: <strong>{{ $voucher->product->store->name }}</strong></p>

                        @if($voucher->personal_message)
                            <div class="mb-5 px-md-5">
                                <p class="fst-italic mb-1" style="color: #4a4a4a;">
                                    <i class="bi bi-quote text-primary small"></i>
                                    {{ $voucher->personal_message }}
                                    <i class="bi bi-quote text-primary bi-quote-reverse small"></i>
                                </p>
                            </div>
                        @endif

                        <div class="my-5 p-3 bg-white d-inline-block rounded-4 shadow-sm border">
                            {!! $qrCode !!}
                        </div>

                        <p class="text-muted small mb-5 px-md-4">
                            Please present this QR code at any participating store branch to redeem your gift.
                        </p>

                        <div class="text-start bg-light p-4 rounded-4">
                            <h5 class="h6 fw-bold mb-3"><i class="bi bi-geo-alt-fill text-primary me-2"></i>Participating Branches</h5>
                            <ul class="list-unstyled mb-0">
                                @foreach($voucher->product->store->branches as $branch)
                                    <li class="mb-3 d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold small">{{ $branch->name }}</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">{{ $branch->address }}</div>
                                        </div>
                                        @if($branch->map_url)
                                            <a href="{{ $branch->map_url }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill ms-3" title="View on Google Maps">
                                                <i class="bi bi-geo-alt-fill"></i>
                                            </a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="mt-5 text-muted small">
                            Valid until: {{ $voucher->expires_at ? $voucher->expires_at->format('M d, Y') : 'N/A' }}<br>
                            Voucher ID: <code>{{ $voucher->unique_token }}</code>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-5">
                    <a href="{{ url('/') }}" class="btn btn-link text-decoration-none text-muted">Visit Beam Gifts</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes shake {
        0% { transform: rotate(0deg); }
        25% { transform: rotate(5deg); }
        50% { transform: rotate(-5deg); }
        75% { transform: rotate(5deg); }
        100% { transform: rotate(0deg); }
    }

    @keyframes fadeOutUp {
        from { opacity: 1; transform: translate3d(0, 0, 0); }
        to { opacity: 0; transform: translate3d(0, -100px, 0); }
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }

    .shake-anim {
        animation: shake 0.2s infinite;
    }

    .animate-fade-out-up {
        animation: fadeOutUp 0.8s forwards;
    }

    .animate-fade-in {
        animation: fadeIn 0.8s forwards;
    }

    .gift-box-container {
        display: inline-block;
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    #gift-wrapped:not(.animate-fade-out-up) .gift-box-container:hover {
        transform: scale(1.1);
    }
</style>

<script>
    document.getElementById('unwrap-btn').addEventListener('click', function() {
        const giftIcon = document.getElementById('gift-icon');
        const wrappedSection = document.getElementById('gift-wrapped');
        const unwrappedSection = document.getElementById('gift-unwrapped');

        // Start shaking
        giftIcon.classList.add('shake-anim');

        // Play unwrap sequence
        setTimeout(() => {
            wrappedSection.classList.add('animate-fade-out-up');
            
            setTimeout(() => {
                wrappedSection.classList.add('d-none');
                unwrappedSection.classList.remove('d-none');
            }, 800);
        }, 1000);
    });
</script>
@endsection
