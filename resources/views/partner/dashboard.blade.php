@extends('layouts.app')

@section('title', 'Partner Dashboard')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 h-100">
                <div class="card-body p-0">
                    <h5 class="fw-bold text-primary mb-4">Partner Menu</h5>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('partner.dashboard') }}" class="list-group-item list-group-item-action active rounded-pill mb-1">Dashboard</a>
                        <a href="{{ route('partner.store.show') }}" class="list-group-item list-group-item-action rounded-pill mb-1 border-0">My Store</a>
                        <a href="{{ route('partner.branches.index') }}" class="list-group-item list-group-item-action rounded-pill mb-1 border-0">Manage Branches</a>
                        <a href="{{ route('partner.products.index') }}" class="list-group-item list-group-item-action rounded-pill mb-1 border-0">Products</a>
                        <a href="{{ route('partner.vouchers.index') }}" class="list-group-item list-group-item-action rounded-pill mb-1 border-0">Vouchers</a>
                        <a href="{{ route('partner.vouchers.scan') }}" class="list-group-item list-group-item-action rounded-pill mb-1 border-0">Scan QR</a>
                        <form action="{{ route('partner.logout') }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100 rounded-pill">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 fw-bold mb-0 text-primary">Welcome, {{ $partner->name }}</h1>
                <span class="badge bg-light text-dark rounded-pill px-3 py-2">{{ $partner->business_name }}</span>
            </div>
            
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="card bg-white shadow-sm border-0 text-center py-4">
                        <div class="card-body">
                            <h2 class="h1 fw-bold text-primary mb-0">{{ $branchCount }}</h2>
                            <p class="text-muted mb-0">Branches</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-white shadow-sm border-0 text-center py-4">
                        <div class="card-body">
                            <h2 class="h1 fw-bold text-primary mb-0">{{ $productCount }}</h2>
                            <p class="text-muted mb-0">Products</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-white shadow-sm border-0 text-center py-4">
                        <div class="card-body">
                            <h2 class="h1 fw-bold text-primary mb-0">0</h2>
                            <p class="text-muted mb-0">Unclaimed Vouchers</p>
                        </div>
                    </div>
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
