@extends('layouts.app')

@section('title', 'Product Details')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            @include('partner.partials.menu')
        </div>
        <div class="col-md-9">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('partner.products.index') }}">Products</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>

            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-5">
                        @if(!empty($product->images))
                            <img src="{{ Storage::url($product->images[0]) }}" class="img-fluid h-100" style="object-fit: cover; min-height: 300px;">
                        @else
                            <div class="bg-light h-100 d-flex align-items-center justify-content-center" style="min-height: 300px;">
                                <span class="text-muted">No image available</span>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-7">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="badge bg-primary-subtle text-primary rounded-pill mb-2 px-3">{{ $product->category }}</span>
                                    <h1 class="h2 fw-bold mb-1">{{ $product->name }}</h1>
                                    <div class="h4 text-primary fw-bold">₱{{ number_format($product->price, 2) }}</div>
                                </div>
                                <div>
                                    @if($product->is_banned)
                                        <span class="badge bg-danger rounded-pill">Banned</span>
                                    @else
                                        <span class="badge bg-success rounded-pill">Active</span>
                                    @endif
                                </div>
                            </div>

                            <p class="text-muted mb-5">{{ $product->description ?: 'No description provided.' }}</p>

                            <hr class="my-4">

                            <div class="d-flex gap-3 mt-4">
                                <a href="{{ route('partner.products.edit', $product) }}" class="btn btn-primary rounded-pill px-5 py-2 fw-bold">
                                    <i class="bi bi-pencil-square me-2"></i> Edit Product
                                </a>
                                <form action="{{ route('partner.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger rounded-pill px-4 py-2">
                                        <i class="bi bi-trash me-2"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 text-center">
                <a href="{{ route('partner.products.index') }}" class="btn btn-light rounded-pill px-4">
                    <i class="bi bi-arrow-left me-2"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
