@extends('layouts.app')

@section('title', 'Order History - Beam Gifts')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 h-100">
                <div class="card-body p-0">
                    <h5 class="fw-bold text-primary mb-4">Account Menu</h5>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('profile') }}" class="list-group-item list-group-item-action border-0 rounded-pill mb-1">My Profile</a>
                        <a href="{{ route('my-gifts') }}" class="list-group-item list-group-item-action border-0 rounded-pill mb-1 border-0">My Gifts</a>
                        <a href="{{ route('reviews.index') }}" class="list-group-item list-group-item-action rounded-pill mb-1 border-0">Reviews Awaiting</a>
                        <a href="{{ route('my-orders') }}" class="list-group-item list-group-item-action active rounded-pill mb-1 border-0">Order History</a>
                        <form action="{{ route('logout') }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100 rounded-pill">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <h1 class="h3 fw-bold mb-4 text-primary">Order History</h1>
            
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Order ID</th>
                                    <th>Date</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="fw-bold text-dark">#{{ $order->id }}</span>
                                        </td>
                                        <td class="small text-muted">{{ $order->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark rounded-pill">{{ $order->items->sum('quantity') }} items</span>
                                        </td>
                                        <td class="fw-bold text-primary">₱{{ number_format($order->total_amount, 2) }}</td>
                                        <td>
                                            @if($order->status === 'paid')
                                                <span class="badge bg-success rounded-pill">Paid</span>
                                            @elseif($order->status === 'pending')
                                                <span class="badge bg-warning rounded-pill">Pending</span>
                                            @else
                                                <span class="badge bg-danger rounded-pill">{{ ucfirst($order->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('my-orders.show', $order) }}" class="btn btn-sm btn-light rounded-pill">View Details</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">No orders found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
