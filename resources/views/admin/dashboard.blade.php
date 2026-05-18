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
                <div class="col-md-4">
                    <div class="card bg-primary text-white shadow-sm border-0">
                        <div class="card-body text-center py-4">
                            <h2 class="h1 fw-bold mb-0">{{ $partnerCount }}</h2>
                            <p class="mb-0">Partners</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-secondary text-white shadow-sm border-0">
                        <div class="card-body text-center py-4">
                            <h2 class="h1 fw-bold mb-0">{{ $productCount }}</h2>
                            <p class="mb-0">Products</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light shadow-sm border-0">
                        <div class="card-body text-center py-4">
                            <h2 class="h1 fw-bold mb-0">₱0.00</h2>
                            <p class="mb-0 text-muted">Total Sales</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
