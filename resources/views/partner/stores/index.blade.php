@extends('layouts.app')

@section('title', 'My Stores')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 h-100">
                <div class="card-body p-0">
                    <h5 class="fw-bold text-primary mb-4">Partner Menu</h5>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('partner.dashboard') }}" class="list-group-item list-group-item-action rounded-pill mb-1 border-0">Dashboard</a>
                        <a href="{{ route('partner.stores.index') }}" class="list-group-item list-group-item-action active rounded-pill mb-1 border-0">My Stores</a>
                        <a href="#" class="list-group-item list-group-item-action rounded-pill mb-1 border-0">Products</a>
                        <a href="#" class="list-group-item list-group-item-action rounded-pill mb-1 border-0">Vouchers</a>
                        <a href="#" class="list-group-item list-group-item-action rounded-pill mb-1 border-0">Scan QR</a>
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
                <h1 class="h3 fw-bold mb-0 text-primary">My Stores</h1>
                <a href="{{ route('partner.stores.create') }}" class="btn btn-primary rounded-pill px-4">Add New Store</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-pill px-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-4">
                @forelse($stores as $store)
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between mb-3">
                                    <h5 class="fw-bold mb-0">{{ $store->name }}</h5>
                                    @if($store->is_banned)
                                        <span class="badge bg-danger rounded-pill">Banned by Admin</span>
                                    @endif
                                </div>
                                <p class="text-muted small mb-4 text-truncate-2">{{ $store->description }}</p>
                                
                                <div class="row g-2 mb-4">
                                    <div class="col-6">
                                        <div class="bg-light p-2 rounded text-center">
                                            <div class="fw-bold">{{ $store->branches_count }}</div>
                                            <div class="small text-muted">Branches</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-light p-2 rounded text-center">
                                            <div class="fw-bold">{{ $store->products_count }}</div>
                                            <div class="small text-muted">Products</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <a href="{{ route('partner.stores.branches.index', $store) }}" class="btn btn-sm btn-outline-primary rounded-pill flex-grow-1">Manage Branches</a>
                                    <a href="{{ route('partner.stores.edit', $store) }}" class="btn btn-sm btn-light rounded-pill">Edit Settings</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">You haven't added any stores yet.</p>
                        <a href="{{ route('partner.stores.create') }}" class="btn btn-primary rounded-pill">Add Your First Store</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
