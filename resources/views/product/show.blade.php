@extends('layouts.app')

@section('title', $product->name . ' - Beam Gifts')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('city.home', $city->slug) }}" class="text-decoration-none">{{ $city->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        <div class="col-md-6">
            @if(!empty($product->images))
                <div id="productCarousel" class="carousel slide border rounded-4 overflow-hidden shadow-sm" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($product->images as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ Storage::url($image) }}" class="d-block w-100" style="height: 500px; object-fit: cover;">
                            </div>
                        @endforeach
                    </div>
                    @if(count($product->images) > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    @endif
                </div>
            @else
                <div class="bg-light rounded-4 d-flex align-items-center justify-content-center border shadow-sm" style="height: 500px;">
                    <i class="bi bi-image text-muted display-1"></i>
                </div>
            @endif
        </div>

        <div class="col-md-6">
            <div class="ps-md-4">
                <span class="badge bg-light text-dark rounded-pill px-3 py-2 mb-3">{{ $product->category_rel->name ?? 'Uncategorized' }}</span>
                <h1 class="display-5 fw-bold text-dark mb-2">{{ $product->name }}</h1>
                <p class="h3 fw-bold text-primary mb-2">₱{{ number_format($product->price, 2) }}</p>
                <div class="mb-4">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star-fill {{ $i <= $product->average_rating ? 'text-warning' : 'text-muted opacity-25' }} fs-5"></i>
                    @endfor
                    <span class="ms-2 text-muted">({{ $product->reviews_count }} reviews)</span>
                </div>
                
                <p class="text-muted mb-5 lead">{{ $product->description }}</p>

                <div class="card bg-light border-0 rounded-4 p-4 mb-5">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-white rounded-circle p-2 me-3 shadow-sm">
                            <i class="bi bi-shop text-primary fs-4"></i>
                        </div>
                        <div>
                            <div class="small text-muted">Sold by</div>
                            <a href="{{ route('store.show', ['city_slug' => $city->slug, 'store_slug' => $product->store->slug]) }}" class="fw-bold text-dark text-decoration-none">{{ $product->store->name }}</a>
                        </div>
                    </div>
                    <form action="{{ route('cart.add', ['city_slug' => $city->slug, 'product' => $product]) }}" method="POST" class="add-to-cart-form">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill py-3 fw-bold shadow-sm">
                            <i class="bi bi-cart-plus me-2"></i> Add to Cart
                        </button>
                    </form>
                </div>

                <div class="mb-4">
                    <h5 class="fw-bold h6 mb-3">Participating Branches in {{ $city->name }}</h5>
                    <div class="list-group list-group-flush bg-transparent">
                        @foreach($product->store->branches as $branch)
                            <div class="list-group-item bg-transparent px-0 border-light d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold small text-dark">{{ $branch->name }}</div>
                                    <div class="small text-muted">{{ $branch->address }}</div>
                                </div>
                                @if($branch->map_url)
                                    <a href="{{ $branch->map_url }}" target="_blank" class="btn btn-sm btn-light rounded-pill text-primary ms-3" title="View on Google Maps">
                                        <i class="bi bi-geo-alt-fill"></i>
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="small text-muted mt-5">
                    <i class="bi bi-info-circle me-1"></i> Digital voucher will be generated and can be shared via a unique link after payment.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
