@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            @include('admin.partials.menu')
        </div>
        <div class="col-md-9">
            <h1 class="h3 fw-bold mb-4">Welcome back, {{ Auth::guard('admin')->user()->name }}</h1>
            
            <div class="row g-4">
                <div class="col-md-3">
                    <a href="{{ route('admin.partners.index') }}" class="text-decoration-none">
                        <div class="card bg-primary text-white shadow-sm border-0 transition-hover">
                            <div class="card-body text-center py-4">
                                <h2 class="h1 fw-bold mb-0">{{ $partnerCount }}</h2>
                                <p class="mb-0">Partners</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('admin.products.index') }}" class="text-decoration-none">
                        <div class="card bg-info text-white shadow-sm border-0 transition-hover">
                            <div class="card-body text-center py-4">
                                <h2 class="h1 fw-bold mb-0">{{ $productCount }}</h2>
                                <p class="mb-0">Total Products</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('admin.products.index') }}?status=pending" class="text-decoration-none">
                        <div class="card {{ $pendingProductCount > 0 ? 'bg-warning text-dark' : 'bg-light text-muted' }} shadow-sm border-0 transition-hover">
                            <div class="card-body text-center py-4">
                                <h2 class="h1 fw-bold mb-0">{{ $pendingProductCount }}</h2>
                                <p class="mb-0">Pending Approval</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('admin.refunds.index') }}" class="text-decoration-none">
                        <div class="card {{ $pendingRefundCount > 0 ? 'bg-danger text-white' : 'bg-light text-muted' }} shadow-sm border-0 transition-hover">
                            <div class="card-body text-center py-4">
                                <h2 class="h1 fw-bold mb-0">{{ $pendingRefundCount }}</h2>
                                <p class="mb-0">Pending Refunds</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white shadow-sm border-0">
                        <div class="card-body text-center py-4">
                            <h2 class="h1 fw-bold mb-0">₱0.00</h2>
                            <p class="mb-0">Total Sales</p>
                        </div>
                    </div>
                </div>
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
        </div>
    </div>
</div>
@endsection
