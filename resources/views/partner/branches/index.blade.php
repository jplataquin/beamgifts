@extends('layouts.app')

@section('title', 'Manage Branches')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 h-100">
                <div class="card-body p-0">
                    <h5 class="fw-bold text-primary mb-4">Partner Menu</h5>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('partner.dashboard') }}" class="list-group-item list-group-item-action border-0 rounded-pill mb-1">Dashboard</a>
                        <a href="{{ route('partner.store.show') }}" class="list-group-item list-group-item-action border-0 rounded-pill mb-1">My Store</a>
                        <a href="{{ route('partner.branches.index') }}" class="list-group-item list-group-item-action active border-0 rounded-pill mb-1">Manage Branches</a>
                        <a href="{{ route('partner.products.index') }}" class="list-group-item list-group-item-action border-0 rounded-pill mb-1">Products</a>
                        <a href="{{ route('partner.vouchers.index') }}" class="list-group-item list-group-item-action border-0 rounded-pill mb-1">Vouchers</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold mb-1 text-primary">Branches</h1>
                    <p class="text-muted small">Manage locations for <strong>{{ $store->name }}</strong></p>
                </div>
                <a href="{{ route('partner.branches.create') }}" class="btn btn-primary rounded-pill px-4">Add New Branch</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-pill px-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm border-0 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Branch Name</th>
                                    <th>City</th>
                                    <th>Address</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($branches as $branch)
                                    <tr>
                                        <td class="ps-4 fw-bold">{{ $branch->name }}</td>
                                        <td><span class="badge bg-secondary rounded-pill">{{ $branch->city->name }}</span></td>
                                        <td class="small text-muted">{{ $branch->address }}</td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('partner.branches.edit', $branch) }}" class="btn btn-sm btn-light rounded-pill">Edit</a>
                                            <form action="{{ route('partner.branches.destroy', $branch) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this branch?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">No branches found for this store.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
