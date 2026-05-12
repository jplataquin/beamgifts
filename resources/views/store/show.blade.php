@extends('layouts.app')

@section('title', $store->name . ' - ' . $city->name)

@section('content')
<div class="bg-light py-5 mb-5">
    <div class="container">
        <h1 class="display-4 fw-bold text-primary">{{ $store->name }}</h1>
        <p class="lead">{{ $store->description }}</p>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h2 class="h3 mb-4">Products</h2>
            <div class="row">
                @foreach($store->products as $product)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <a href="{{ route('product.show', ['city_slug' => $city->slug, 'product_slug' => $product->slug]) }}" class="text-decoration-none text-dark">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold">{{ $product->name }}</h5>
                                    <p class="fw-bold text-primary h5">₱{{ number_format($product->price, 2) }}</p>
                                </div>
                            </a>
                            <div class="card-footer bg-white border-0 pb-4 px-3">
                                <div class="mb-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star-fill {{ $i <= $product->average_rating ? 'text-warning' : 'text-muted opacity-25' }}"></i>
                                    @endfor
                                    <span class="small text-muted ms-2">({{ $product->reviews_count }} reviews)</span>
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
