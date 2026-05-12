@extends('layouts.app')

@section('title', 'Manage Products (Admin)')

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
                        <a href="{{ route('admin.products.index') }}" class="list-group-item list-group-item-action active border-0 rounded-pill mb-1">Products</a>
                        <a href="#" class="list-group-item list-group-item-action border-0 rounded-pill mb-1 border-0">Orders</a>
                        <a href="{{ route('admin.settings.index') }}" class="list-group-item list-group-item-action border-0 rounded-pill mb-1 border-0">Settings</a>
                        <form action="{{ route('admin.logout') }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100 rounded-pill">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <h1 class="h3 fw-bold mb-4 text-primary">Global Product Management</h1>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-pill px-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Product</th>
                                    <th>Partner / Store</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold">{{ $product->name }}</div>
                                            <div class="small text-muted">{{ $product->category }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $product->store->name }}</div>
                                            <div class="small text-muted">Owner ID: {{ $product->store->partner_id }}</div>
                                        </td>
                                        <td>₱{{ number_format($product->price, 2) }}</td>
                                        <td>
                                            @if($product->is_banned)
                                                <span class="badge bg-danger rounded-pill">Banned</span>
                                            @else
                                                <span class="badge bg-success rounded-pill">Active</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light rounded-pill dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    Manage
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                                    <li><a class="dropdown-item" href="{{ route('admin.products.edit', $product) }}">Edit Details</a></li>
                                                    <li>
                                                        <form action="{{ route('admin.products.ban', $product) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="dropdown-item {{ $product->is_banned ? 'text-success' : 'text-warning' }}">
                                                                {{ $product->is_banned ? 'Unban' : 'Ban' }} Product
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product permanently?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">Delete</button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">No products found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                </div>
            </div>
            
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
