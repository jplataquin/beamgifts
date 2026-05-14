@extends('layouts.app')

@section('title', 'My Digital Gifts - Beam Gifts')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            @include('partials.account-menu')
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 fw-bold mb-0 text-primary">My Purchased Gifts</h1>
                @if(request('status'))
                    <a href="{{ route('my-gifts') }}" class="btn btn-link btn-sm text-decoration-none">
                        <i class="bi bi-x-circle me-1"></i> Clear filters
                    </a>
                @endif
            </div>
            <p class="text-muted mb-5">Personalize your gifts with a note and a photo before sending the link.</p>
            
            @if(session('success'))
                <div class="alert alert-success rounded-pill px-4 mb-4">{{ session('success') }}</div>
            @endif

            <div class="row g-4">
                @forelse($vouchers as $voucher)
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100 hover-card overflow-hidden position-relative">
                            <a href="{{ route('vouchers.manage', $voucher) }}" class="stretched-link"></a>
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
                                            <div class="d-flex justify-content-between mb-3">
                                                <span class="badge {{ $voucher->status === 'active' ? 'bg-success' : 'bg-secondary' }} rounded-pill px-3">
                                                    {{ strtoupper($voucher->status) }}
                                                </span>
                                                <small class="text-muted">{{ $voucher->created_at->format('M d, Y') }}</small>
                                            </div>
                                            
                                            <h5 class="fw-bold mb-1">{{ $voucher->product->name }}</h5>
                                            <p class="text-muted small mb-3">Store: {{ $voucher->product->store->name }}</p>
                                            
                                            @if($voucher->personal_message)
                                                <div class="bg-light p-2 rounded-3 mb-0 small italic" style="border-left: 3px solid var(--bs-primary);">
                                                    <i class="bi bi-quote text-primary"></i> 
                                                    {{ Str::limit($voucher->personal_message, 45) }}
                                                    <i class="bi bi-quote text-primary bi-quote-reverse"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="d-grid gap-2 mt-auto" style="z-index: 2;">
                                            <a href="{{ route('vouchers.manage', $voucher) }}" class="btn btn-outline-primary rounded-pill btn-sm">
                                                <i class="bi bi-stars me-1"></i> Personalize & Manage
                                            </a>
                                            
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control bg-light border-0" value="{{ route('voucher.show', $voucher->unique_token) }}" id="link-{{ $voucher->id }}" readonly>
                                                <button class="btn btn-outline-primary" type="button" onclick="copyLink('{{ $voucher->id }}')">Copy Link</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-gift display-1 text-light"></i>
                        </div>
                        <h2 class="h5 fw-bold">No gifts found</h2>
                        <p class="text-muted mb-4">You haven't purchased any digital vouchers yet.</p>
                        <a href="{{ url('/') }}" class="btn btn-primary rounded-pill px-5 py-2">Explore Gifts</a>
                    </div>
                @endforelse
            </div>
            
            <div class="mt-5">
                {{ $vouchers->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    function copyLink(id) {
        var copyText = document.getElementById("link-" + id);
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        
        const btn = event.target;
        const originalText = btn.innerText;
        btn.innerText = "Copied!";
        btn.classList.replace('btn-outline-primary', 'btn-success');
        setTimeout(() => {
            btn.innerText = originalText;
            btn.classList.replace('btn-success', 'btn-outline-primary');
        }, 2000);
    }
</script>
@endsection
