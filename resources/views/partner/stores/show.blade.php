@extends('layouts.app')

@section('title', 'My Store')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            @include('partner.partials.menu')
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 fw-bold mb-0 text-primary">Store Settings</h1>
                <a href="{{ route('partner.store.edit') }}" class="btn btn-primary rounded-pill px-4">Edit Store Info</a>
            </div>

            <div class="card shadow-sm border-0 overflow-hidden">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-md-4 bg-light d-flex align-items-center justify-content-center p-4">
                            @if($store->logo)
                                <img src="{{ Storage::url($store->logo) }}" alt="{{ $store->name }}" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                            @else
                                <div class="text-center text-muted">
                                    <i class="bi bi-shop h1 d-block mb-2"></i>
                                    <span>No Logo Uploaded</span>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8 p-4">
                            <div class="mb-4">
                                <h3 class="fw-bold mb-1">{{ $store->name }}</h3>
                                <p class="text-muted small">URL: <code class="bg-light px-2 py-1 rounded">{{ $store->slug }}</code></p>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold text-muted text-uppercase small">Description</h6>
                                <p class="mb-0">{{ $store->description ?: 'No description provided.' }}</p>
                            </div>

                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded-3">
                                        <div class="h4 fw-bold mb-0 text-primary">{{ $store->branches_count }}</div>
                                        <div class="small text-muted">Active Branches</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded-3">
                                        <div class="h4 fw-bold mb-0 text-primary">{{ $store->products_count }}</div>
                                        <div class="small text-muted">Active Products</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 d-flex gap-2">
                <a href="{{ route('partner.branches.index') }}" class="btn btn-outline-primary rounded-pill flex-grow-1">Manage Branches</a>
                <a href="{{ route('partner.products.index') }}" class="btn btn-outline-primary rounded-pill flex-grow-1">Manage Products Catalog</a>
            </div>
        </div>
    </div>
</div>
@endsection
