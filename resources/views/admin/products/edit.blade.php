@extends('layouts.app')

@section('title', 'Edit Product (Admin)')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('admin.products.index') }}" class="btn btn-light rounded-pill me-3">
                    &larr; Back
                </a>
                <h1 class="h3 fw-bold mb-0 text-primary">Admin Edit: {{ $product->name }}</h1>
            </div>

            <div class="card shadow-sm border-0 p-4">
                <div class="card-body">
                    <div class="alert alert-info rounded-pill px-4 mb-4 small">
                        <i class="bi bi-info-circle me-2"></i> As an administrator, you are editing a partner's product.
                    </div>

                    <form action="{{ route('admin.products.update', $product) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold">Product Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="price" class="form-label fw-bold">Price (₱)</label>
                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="category" class="form-label fw-bold">Category</label>
                                <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category', $product->category) }}" required>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-primary rounded-pill py-3 fw-bold">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
