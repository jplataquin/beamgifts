@extends('layouts.app')

@section('title', 'Gifts in ' . $city->name)

@section('content')
<style>
    @media (max-width: 767.98px) {
        .mobile-horizontal-scroll {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 1rem;
            margin-left: -0.75rem;
            margin-right: -0.75rem;
            scroll-snap-type: x mandatory;
        }
        .mobile-horizontal-scroll::-webkit-scrollbar {
            display: none;
        }
        .scroll-item {
            flex: 0 0 85% !important;
            min-width: 85% !important;
            max-width: 85% !important;
            padding-left: 0.75rem;
            padding-right: 0.75rem;
            scroll-snap-align: start;
            margin-bottom: 0 !important;
        }
    }
</style>
<div id="heroCarousel" class="carousel slide hero-carousel mb-5" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="d-flex align-items-center justify-content-center h-100 text-white text-center">
                <div>
                    <h2 class="display-3 fw-bold">Special Gifts in {{ $city->name }}</h2>
                    <p class="lead">Discover unique digital vouchers for your loved ones.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    @if(isset($stores) && $stores->count() > 0)
        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h3 class="fw-bold mb-1">Available Brands in {{ $city->name }}</h3>
                    <p class="text-muted mb-0">Discover gifts from our local partners</p>
                </div>
            </div>
            
            <div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 g-4 mobile-horizontal-scroll">
                @foreach($stores as $store)
                    <div class="col scroll-item">
                        <a href="{{ route('store.show', ['city_slug' => $city->slug, 'store_slug' => $store->slug]) }}" class="text-decoration-none">
                            <div class="card h-100 border-0 shadow-sm rounded-4 text-center store-card transition-hover">
                                <div class="card-body p-4">
                                    <div class="bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; overflow: hidden; border: 1px solid #eee;">
                                        @if($store->logo)
                                            <img src="{{ Storage::url($store->logo) }}" alt="{{ $store->name }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <i class="bi bi-shop text-muted fs-1"></i>
                                        @endif
                                    </div>
                                    <h6 class="fw-bold text-dark mb-0 text-truncate">{{ $store->name }}</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Search and Filter Bar -->
    <div class="card border-0 shadow-sm rounded-4 mb-5 p-3 p-md-4">
        <form action="{{ route('city.home', ['city_slug' => $city->slug]) }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-lg-4 col-md-12">
                    <label for="q" class="form-label small fw-bold text-muted">Keyword</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" id="q" class="form-control border-start-0 ps-0" placeholder="Search products..." value="{{ request('q') }}">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label for="category" class="form-label small fw-bold text-muted">Category</label>
                    <select name="category" id="category" class="form-select" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        @foreach($availableCategories as $cat)
                            <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label for="store" class="form-label small fw-bold text-muted">Brand / Store</label>
                    <select name="store" id="store" class="form-select" onchange="this.form.submit()">
                        <option value="">All Brands</option>
                        @foreach($stores as $s)
                            <option value="{{ $s->slug }}" {{ request('store') == $s->slug ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-12">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill fw-bold">Search</button>
                        @if(request()->anyFilled(['q', 'category', 'store']))
                            <a href="{{ route('city.home', ['city_slug' => $city->slug]) }}" class="btn btn-link btn-sm text-decoration-none text-muted p-0">Clear filters</a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>

    @forelse($products as $categoryName => $categoryProducts)
        <div class="mb-5">
            <h3 class="h4 fw-bold text-primary mb-4 border-bottom pb-2">{{ $categoryName }}</h3>
            <div class="row mobile-horizontal-scroll">
                @foreach($categoryProducts as $product)
                    <div class="col-md-4 mb-4 scroll-item">
                        <div class="card h-100 shadow-sm border-0 product-card">
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
                                    <p class="text-muted small mb-4 product-brand">By <span class="fw-bold">{{ $product->store->name }}</span></p>
                                </div>
                            </a>
                            <div class="card-footer bg-white border-0 pb-4 px-3 text-center">
                                <div class="mb-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star-fill {{ $i <= $product->average_rating ? 'text-warning' : 'text-muted opacity-25' }}"></i>
                                    @endfor
                                </div>
                                <div class="small text-muted">({{ $product->reviews_count }} reviews)</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="mb-4">
                <i class="bi bi-search display-1 text-light"></i>
            </div>
            <h2 class="h5 fw-bold">No results found</h2>
            <p class="text-muted">We couldn't find any products matching your current search or filters in {{ $city->name }}.</p>
            <a href="{{ route('city.home', ['city_slug' => $city->slug]) }}" class="btn btn-primary rounded-pill px-4">Browse All Gifts</a>
        </div>
    @endforelse
</div>

<style>
    .transition-hover {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .transition-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
</style>
@endsection
