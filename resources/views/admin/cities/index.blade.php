@extends('layouts.app')

@section('title', 'Manage Cities')

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
                        <a href="{{ route('admin.cities.index') }}" class="list-group-item list-group-item-action active border-0 rounded-pill mb-1">Cities</a>
                        <a href="{{ route('admin.categories.index') }}" class="list-group-item list-group-item-action border-0 rounded-pill mb-1">Categories</a>
                        <a href="{{ route('admin.products.index') }}" class="list-group-item list-group-item-action border-0 rounded-pill mb-1">Products</a>
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 fw-bold mb-0">Cities</h1>
                <a href="{{ route('admin.cities.create') }}" class="btn btn-primary rounded-pill px-4">Add New City</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-pill px-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">City Name</th>
                                    <th>Slug</th>
                                    <th>Branches</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cities as $city)
                                    <tr>
                                        <td class="ps-4 fw-bold text-primary">{{ $city->name }}</td>
                                        <td><code>{{ $city->slug }}</code></td>
                                        <td><span class="badge bg-secondary rounded-pill">{{ $city->branches_count }}</span></td>
                                        <td>
                                            @if($city->is_active)
                                                <span class="badge bg-success rounded-pill">Active</span>
                                            @else
                                                <span class="badge bg-light text-dark rounded-pill">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('admin.cities.edit', $city) }}" class="btn btn-sm btn-light rounded-pill">Edit</a>
                                            <form action="{{ route('admin.cities.destroy', $city) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this city and all associated branches?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">No cities found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                {{ $cities->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
