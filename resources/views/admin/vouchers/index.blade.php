@extends('layouts.app')

@section('title', 'Platform Vouchers')

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
                        <a href="{{ route('admin.orders.index') }}" class="list-group-item list-group-item-action rounded-pill mb-1 border-0">Orders</a>
                        <a href="{{ route('admin.vouchers.index') }}" class="list-group-item list-group-item-action active rounded-pill mb-1 border-0">Vouchers</a>
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
                    <h1 class="h3 fw-bold mb-0 text-primary">Platform Vouchers</h1>
                    <p class="text-muted">Comprehensive report of all generated vouchers.</p>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.vouchers.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control rounded-pill" placeholder="Search Voucher ID..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select rounded-pill">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="claimed" {{ request('status') === 'claimed' ? 'selected' : '' }}>Claimed</option>
                                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="from_date" class="form-control rounded-pill" value="{{ request('from_date') }}" placeholder="From Date">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="to_date" class="form-control rounded-pill" value="{{ request('to_date') }}" placeholder="To Date">
                        </div>
                        <div class="col-md-2 d-flex">
                            <button type="submit" class="btn btn-primary rounded-pill w-100 me-2">Filter</button>
                            <a href="{{ route('admin.vouchers.index') }}" class="btn btn-light rounded-pill">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">Voucher ID</th>
                                <th class="py-3">Product & Store</th>
                                <th class="py-3">Gifter</th>
                                <th class="py-3">Received By</th>
                                <th class="py-3">Price</th>
                                <th class="py-3">Status</th>
                                <th class="py-3 pe-4">Purchased On</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vouchers as $voucher)
                                <tr onclick="window.location='{{ route('admin.vouchers.show', $voucher) }}'" style="cursor: pointer;">
                                    <td class="ps-4 fw-bold text-dark">
                                        <code>#{{ str_pad($voucher->id, 6, '0', STR_PAD_LEFT) }}</code>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $voucher->product->name ?? 'Unknown Product' }}</div>
                                        <div class="small text-muted">{{ $voucher->product->store->name ?? 'Unknown Store' }}</div>
                                    </td>
                                    <td>{{ $voucher->order->gifter->name ?? $voucher->order->gifter->email ?? 'Guest' }}</td>
                                    <td>
                                        @if($voucher->status === 'claimed')
                                            <span class="fw-bold text-dark">{{ $voucher->claimed_by ?? 'Unknown' }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold text-primary">₱{{ number_format($voucher->price ?? $voucher->product->price, 2) }}</td>
                                    <td>
                                        @if($voucher->status === 'active')
                                            <span class="badge bg-success rounded-pill">Active</span>
                                        @elseif($voucher->status === 'claimed')
                                            <span class="badge bg-secondary rounded-pill">Claimed</span>
                                            @if($voucher->claimed_at)
                                                <div class="small text-muted mt-1">{{ $voucher->claimed_at->format('M d, Y') }}</div>
                                            @endif
                                        @else
                                            <span class="badge bg-danger rounded-pill">{{ ucfirst($voucher->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-muted pe-4">
                                        {{ $voucher->created_at->format('M d, Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">No vouchers found on the platform.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-4">
                {{ $vouchers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection