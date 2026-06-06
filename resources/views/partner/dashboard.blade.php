@extends('layouts.app')

@section('title', 'Partner Dashboard')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            @include('partner.partials.menu')
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 fw-bold mb-0 text-primary">Welcome, {{ $partner->name }}</h1>
                <span class="badge bg-light text-dark rounded-pill px-3 py-2">{{ $partner->business_name }}</span>
            </div>
            
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <a href="{{ route('partner.branches.index') }}" class="text-decoration-none">
                        <div class="card bg-white shadow-sm border-0 text-center py-4 transition-hover">
                            <div class="card-body">
                                <h2 class="h1 fw-bold text-primary mb-0">{{ $branchCount }}</h2>
                                <p class="text-muted mb-0">Branches</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('partner.products.index') }}" class="text-decoration-none">
                        <div class="card bg-white shadow-sm border-0 text-center py-4 transition-hover">
                            <div class="card-body">
                                <h2 class="h1 fw-bold text-primary mb-0">{{ $productCount }}</h2>
                                <p class="text-muted mb-0">Products</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('partner.vouchers.index') }}?status=active" class="text-decoration-none">
                        <div class="card bg-white shadow-sm border-0 text-center py-4 transition-hover">
                            <div class="card-body">
                                <h2 class="h1 fw-bold text-primary mb-0">{{ $unclaimedVoucherCount }}</h2>
                                <p class="text-muted mb-0">Unclaimed Vouchers</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="card shadow-sm border-0 p-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">Quick Actions</h5>
                    <div class="row g-3">
                        <div class="col-md-6 col-lg-3">
                            <a href="{{ route('partner.branches.create') }}" class="text-decoration-none">
                                <div class="card h-100 border-0 shadow-sm bg-light transition-hover">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="icon-circle bg-white text-primary shadow-sm me-2">
                                                <i class="bi bi-geo-alt"></i>
                                            </div>
                                            <h6 class="fw-bold mb-0 text-dark">Add Branch</h6>
                                        </div>
                                        <p class="small text-muted mb-0">New location</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <a href="{{ route('partner.products.create') }}" class="text-decoration-none">
                                <div class="card h-100 border-0 shadow-sm bg-light transition-hover">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="icon-circle bg-white text-primary shadow-sm me-2">
                                                <i class="bi bi-plus-circle"></i>
                                            </div>
                                            <h6 class="fw-bold mb-0 text-dark">Add Product</h6>
                                        </div>
                                        <p class="small text-muted mb-0">New item</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <a href="{{ route('partner.vouchers.index') }}" class="text-decoration-none">
                                <div class="card h-100 border-0 shadow-sm bg-light transition-hover">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="icon-circle bg-white text-primary shadow-sm me-2">
                                                <i class="bi bi-gift"></i>
                                            </div>
                                            <h6 class="fw-bold mb-0 text-dark">Vouchers</h6>
                                        </div>
                                        <p class="small text-muted mb-0">Track sales</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <a href="{{ route('partner.store.show') }}" class="text-decoration-none">
                                <div class="card h-100 border-0 shadow-sm bg-light transition-hover">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="icon-circle bg-white text-primary shadow-sm me-2">
                                                <i class="bi bi-gear"></i>
                                            </div>
                                            <h6 class="fw-bold mb-0 text-dark">Settings</h6>
                                        </div>
                                        <p class="small text-muted mb-0">Store info</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
    .transition-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }
    .transition-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.1)!important;
        background-color: #fff !important;
    }
    .icon-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
</style>
@endpush
