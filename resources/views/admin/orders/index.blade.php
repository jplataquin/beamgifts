@extends('layouts.app')

@section('title', 'Platform Orders')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0">
                <div class="card-body p-0">
                    <h5 class="fw-bold text-primary mb-4">Admin Menu</h5>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action rounded-pill mb-1">Dashboard</a>
                        <a href="{{ route('admin.partners.index') }}" class="list-group-item list-group-item-action rounded-pill mb-1">Partners</a>
                        <a href="{{ route('admin.cities.index') }}" class="list-group-item list-group-item-action rounded-pill mb-1">Cities</a>
                        <a href="{{ route('admin.categories.index') }}" class="list-group-item list-group-item-action rounded-pill mb-1">Categories</a>
                        <a href="{{ route('admin.products.index') }}" class="list-group-item list-group-item-action rounded-pill mb-1">Products</a>
                        <a href="{{ route('admin.orders.index') }}" class="list-group-item list-group-item-action active rounded-pill mb-1 border-0">Orders</a>
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold mb-0 text-primary">Platform Orders</h1>
                    <p class="text-muted">Manage and monitor all gift purchases across the platform.</p>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="reference_number" class="form-control rounded-pill" placeholder="Search by Reference #..." value="{{ request('reference_number') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select rounded-pill">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary rounded-pill w-100">Filter</button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-light rounded-pill w-100">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">Reference #</th>
                                <th class="py-3">Gifter</th>
                                <th class="py-3">Date</th>
                                <th class="py-3">Amount</th>
                                <th class="py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr onclick="window.location='{{ route('admin.orders.show', $order) }}'" style="cursor: pointer;">
                                    <td class="ps-4 fw-bold text-dark">{{ $order->reference_number }}</td>
                                    <td>{{ $order->gifter->name ?? $order->gifter->email ?? 'Guest' }}</td>
                                    <td class="text-muted">{{ $order->created_at->format('M d, Y h:i A') }}</td>
                                    <td class="fw-bold text-primary">₱{{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        @if($order->status === 'paid')
                                            <span class="badge bg-success rounded-pill">Paid</span>
                                        @elseif($order->status === 'pending')
                                            <span class="badge bg-warning text-dark rounded-pill">Pending</span>
                                        @else
                                            <span class="badge bg-danger rounded-pill">{{ ucfirst($order->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">No orders found on the platform.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection