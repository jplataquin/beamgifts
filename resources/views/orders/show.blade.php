@extends('layouts.app')

@section('title', 'Order Details - Beam Gifts')

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
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('my-orders') }}" class="btn btn-light rounded-pill me-3">&larr; Back</a>
                <h1 class="h3 fw-bold mb-0 text-primary">Order #{{ $order->id }}</h1>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-3 small text-muted text-uppercase">Order Info</h5>
                            <p class="mb-1">Date: <strong>{{ $order->created_at->format('M d, Y H:i') }}</strong></p>
                            <p class="mb-1">Status: 
                                @if($order->status === 'paid')
                                    <span class="badge bg-success rounded-pill">Paid</span>
                                @elseif($order->status === 'pending')
                                    <span class="badge bg-warning rounded-pill">Pending</span>
                                @else
                                    <span class="badge bg-danger rounded-pill">{{ ucfirst($order->status) }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h5 class="fw-bold mb-3 small text-muted text-uppercase">Summary</h5>
                            <h2 class="fw-bold text-primary">₱{{ number_format($order->total_amount, 2) }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <h5 class="fw-bold mb-3">Order Items</h5>
            <div class="card shadow-sm border-0 mb-4 overflow-hidden">
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Product</th>
                                <th>Quantity</th>
                                <th class="text-end pe-4">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">{{ $item->product_name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="text-end pe-4">₱{{ number_format($item->price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if($order->vouchers->count() > 0)
                <h5 class="fw-bold mb-3">Generated Vouchers</h5>
                <div class="row g-3">
                    @foreach($order->vouchers as $voucher)
                        <div class="col-md-6">
                            <div class="card border border-light rounded-4">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold text-primary small">{{ $voucher->product->name }}</div>
                                            <div class="text-muted small" style="font-size: 0.7rem;">Code: {{ $voucher->unique_token }}</div>
                                        </div>
                                        <a href="{{ route('voucher.show', $voucher->unique_token) }}" class="btn btn-sm btn-outline-primary rounded-pill">View</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
