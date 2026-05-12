@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0">
                <div class="card-body p-0">
                    <h5 class="fw-bold text-primary mb-4">Admin Menu</h5>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action active rounded-pill mb-1">Dashboard</a>
                        <a href="{{ route('admin.partners.index') }}" class="list-group-item list-group-item-action rounded-pill mb-1">Partners</a>
                        <a href="{{ route('admin.cities.index') }}" class="list-group-item list-group-item-action rounded-pill mb-1">Cities</a>
                        <a href="{{ route('admin.categories.index') }}" class="list-group-item list-group-item-action rounded-pill mb-1">Categories</a>
                        <a href="{{ route('admin.products.index') }}" class="list-group-item list-group-item-action rounded-pill mb-1">Products</a>
                        <a href="#" class="list-group-item list-group-item-action rounded-pill mb-1 border-0">Orders</a>
                        <a href="{{ route('admin.settings.index') }}" class="list-group-item list-group-item-action rounded-pill mb-1 border-0">Settings</a>
                        <form action="{{ route('admin.logout') }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100 rounded-pill">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
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
