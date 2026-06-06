@extends('layouts.app')

@section('title', 'Manage Products (Admin)')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            @include('admin.partials.menu')
        </div>
        <div class="col-md-9">
            <h1 class="h3 fw-bold mb-4 text-primary">
                {{ request('status') === 'pending' ? 'Pending Approval' : 'Global Product Management' }}
            </h1>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.products.index') }}" method="GET" class="row g-3 align-items-end">
                        @if(request('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        
                        <div class="col-md-5">
                            <label class="form-label small fw-bold">Filter by Store</label>
                            <select name="store_id" class="form-select rounded-pill">
                                <option value="">All Stores</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                                        {{ $store->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="bi bi-filter me-1"></i> Apply Filter
                            </button>
                            @if(request('store_id') || request('status'))
                                <a href="{{ route('admin.products.index') }}" class="btn btn-light rounded-pill px-4 ms-2">
                                    Clear
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

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

                                            @if(!$product->is_approved)
                                                <span class="badge bg-warning text-dark rounded-pill">Pending Approval</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light rounded-pill dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    Manage
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                                    <li>
                                                        <form action="{{ route('admin.products.approve', $product) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="dropdown-item {{ $product->is_approved ? 'text-warning' : 'text-success fw-bold' }}">
                                                                {{ $product->is_approved ? 'Unapprove' : 'Approve' }} Product
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
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
