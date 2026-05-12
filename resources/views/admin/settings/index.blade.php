@extends('layouts.app')

@section('title', 'Site Settings')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0">
                <div class="card-body p-0">
                    <h5 class="fw-bold text-primary mb-4">Admin Menu</h5>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action border-0 rounded-pill mb-1">Dashboard</a>
                        <a href="{{ route('admin.partners.index') }}" class="list-group-item list-group-item-action border-0 rounded-pill mb-1">Partners</a>
                        <a href="{{ route('admin.cities.index') }}" class="list-group-item list-group-item-action border-0 rounded-pill mb-1">Cities</a>
                        <a href="{{ route('admin.categories.index') }}" class="list-group-item list-group-item-action border-0 rounded-pill mb-1">Categories</a>
                        <a href="{{ route('admin.products.index') }}" class="list-group-item list-group-item-action border-0 rounded-pill mb-1">Products</a>
                        <a href="#" class="list-group-item list-group-item-action border-0 rounded-pill mb-1 border-0">Orders</a>
                        <a href="{{ route('admin.settings.index') }}" class="list-group-item list-group-item-action active border-0 rounded-pill mb-1 border-0">Settings</a>
                        <form action="{{ route('admin.logout') }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100 rounded-pill">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <h1 class="h3 fw-bold mb-4 text-primary">Site Settings & Content</h1>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-pill px-4 mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                
                <!-- General Settings -->
                <div class="card shadow-sm border-0 p-4 mb-5">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">General Configuration</h5>
                        <div class="mb-4">
                            <label for="global_markup_percentage" class="form-label fw-bold">Global Price Markup (%)</label>
                            <p class="text-muted small">This percentage will be added to the base price of all products as a platform fee.</p>
                            <div class="input-group" style="max-width: 200px;">
                                <input type="number" step="0.01" class="form-control @error('global_markup_percentage') is-invalid @enderror" id="global_markup_percentage" name="global_markup_percentage" value="{{ old('global_markup_percentage', $markup) }}" required>
                                <span class="input-group-text">%</span>
                            </div>
                            @error('global_markup_percentage')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Page Content Settings -->
                <div class="card shadow-sm border-0 p-4 mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">Page Content Management</h5>
                        
                        <div class="mb-5">
                            <label for="page_about" class="form-label fw-bold">About Us Page</label>
                            <textarea class="form-control" name="page_about" id="page_about" rows="8">{{ old('page_about', $about) }}</textarea>
                            <div class="form-text small">Explain what Beam Gifts is all about.</div>
                        </div>

                        <div class="mb-5">
                            <label for="page_terms" class="form-label fw-bold">Terms of Service</label>
                            <textarea class="form-control" name="page_terms" id="page_terms" rows="8">{{ old('page_terms', $terms) }}</textarea>
                            <div class="form-text small">Legal rules for using the platform.</div>
                        </div>

                        <div class="mb-4">
                            <label for="page_privacy" class="form-label fw-bold">Privacy Policy</label>
                            <textarea class="form-control" name="page_privacy" id="page_privacy" rows="8">{{ old('page_privacy', $privacy) }}</textarea>
                            <div class="form-text small">How you handle user data.</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mb-5">
                    <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm">Save All Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
