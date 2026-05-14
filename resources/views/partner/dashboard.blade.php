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
                    <h5 class="fw-bold mb-3">Quick Actions</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('partner.branches.create') }}" class="btn btn-light w-100 py-3 rounded-4 text-start border">
                                <div class="fw-bold">Add New Branch</div>
                                <div class="small text-muted">Create a new location for your store</div>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('partner.vouchers.scan') }}" class="btn btn-light w-100 py-3 rounded-4 text-start border">
                                <div class="fw-bold">Scan Voucher</div>
                                <div class="small text-muted">Redeem a digital gift card</div>
                            </a>
                        </div>
                    </div>
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
