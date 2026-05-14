@extends('layouts.app')

@section('title', $store->name . ' - ' . $city->name)

@section('content')
<div class="bg-light py-5 mb-5">
    <div class="container">
        <div class="d-flex align-items-center">
            <div class="bg-white rounded-circle p-2 me-4 shadow-sm d-flex align-items-center justify-content-center" style="width: 120px; height: 120px; flex-shrink: 0; overflow: hidden;">
                @if($store->logo)
                    <img src="{{ Storage::url($store->logo) }}" alt="{{ $store->name }}" class="img-fluid rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <i class="bi bi-shop text-primary display-4"></i>
                @endif
            </div>
            <div>
                <h1 class="display-4 fw-bold text-primary mb-1">{{ $store->name }}</h1>
                <p class="lead mb-0">{{ $store->description }}</p>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h2 class="h3 fw-bold mb-4">Our Products</h2>
            <div class="row">
                @foreach($store->products as $product)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-0 product-card transition-hover">
                            <a href="{{ route('product.show', ['city_slug' => $city->slug, 'product_slug' => $product->slug]) }}" class="text-decoration-none text-dark">
                                @if(!empty($product->images))
                                    <img src="{{ Storage::url($product->images[0]) }}" class="card-img-top" style="height: 200px; object-fit: cover; border-top-left-radius: 15px; border-top-right-radius: 15px;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px; border-top-left-radius: 15px; border-top-right-radius: 15px;">
                                        <i class="bi bi-image text-muted display-4"></i>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge bg-light text-dark rounded-pill">{{ $product->category_rel->name ?? 'Uncategorized' }}</span>
                                        <p class="fw-bold text-primary mb-0">₱{{ number_format($product->price, 2) }}</p>
                                    </div>
                                    <h5 class="card-title fw-bold mb-1">{{ $product->name }}</h5>
                                </div>
                            </a>
                            <div class="card-footer bg-white border-0 pb-4 px-3">
                                <div class="mb-1 text-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star-fill {{ $i <= $product->average_rating ? 'text-warning' : 'text-muted opacity-25' }}"></i>
                                    @endfor
                                    <div class="small text-muted mt-1">({{ $product->reviews_count }} reviews)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h2 class="h5 mb-3">Participating Branches in {{ $city->name }}</h2>
                    <ul class="list-unstyled">
                        @foreach($store->branches as $branch)
                            <li class="mb-3">
                                <p class="fw-bold mb-0 text-secondary">{{ $branch->name }}</p>
                                <p class="small text-muted mb-0">{{ $branch->address }}</p>
                                @if($branch->phone)
                                    <p class="small text-muted mb-1">{{ $branch->phone }}</p>
                                @endif
                                @if($branch->map_url)
                                    <a href="{{ $branch->map_url }}" target="_blank" class="small text-primary text-decoration-none d-block">
                                        <i class="bi bi-geo-alt-fill me-1"></i>View on Google Maps
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .transition-hover {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .transition-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
</style>
