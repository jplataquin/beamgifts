@extends('layouts.app')

@section('title', 'Manage Partners')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0">
                <div class="card-body p-0">
                    <h5 class="fw-bold text-primary mb-4">Admin Menu</h5>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action border-0 rounded-pill mb-1">Dashboard</a>
                        <a href="{{ route('admin.partners.index') }}" class="list-group-item list-group-item-action active border-0 rounded-pill mb-1">Partners</a>
                        <a href="{{ route('admin.cities.index') }}" class="list-group-item list-group-item-action border-0 rounded-pill mb-1">Cities</a>
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
                <h1 class="h3 fw-bold mb-0">Partners</h1>
                <a href="{{ route('admin.partners.create') }}" class="btn btn-primary rounded-pill px-4">Add New Partner</a>
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
                                    <th class="ps-4">Partner Name</th>
                                    <th>Business Name</th>
                                    <th>Store Status</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($partners as $partner)
                                    <tr>
                                    <td>
                                        <div class="fw-bold">{{ $partner->name }}</div>
                                        <div class="small text-muted">{{ $partner->email }}</div>
                                        @if($partner->phone1)
                                            <div class="small text-muted"><i class="bi bi-telephone small me-1"></i>{{ $partner->phone1 }}</div>
                                        @elseif($partner->phone2)
                                            <div class="small text-muted"><i class="bi bi-telephone small me-1"></i>{{ $partner->phone2 }}</div>
                                        @endif
                                    </td>
                                    <td>{{ $partner->business_name }}</td>
                                    <td>
                                        @if($partner->store)
                                            <span class="badge bg-success rounded-pill">Active Store</span>
                                        @else
                                            <span class="badge bg-warning text-dark rounded-pill">No Store</span>
                                        @endif
                                    </td>
                                    <td>
                                            @if($partner->is_banned)
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
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                    <li><a class="dropdown-item" href="{{ route('admin.partners.edit', $partner) }}">Edit</a></li>
                                                    <li>
                                                        <form action="{{ route('admin.partners.ban', $partner) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="dropdown-item {{ $partner->is_banned ? 'text-success' : 'text-warning' }}">
                                                                {{ $partner->is_banned ? 'Unban' : 'Ban' }} Partner
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('admin.partners.destroy', $partner) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this partner?')">
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
                                        <td colspan="5" class="text-center py-5 text-muted">No partners found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                </div>
            </div>
            
            <div class="mt-4">
                {{ $partners->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
