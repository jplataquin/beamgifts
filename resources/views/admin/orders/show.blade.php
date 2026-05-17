@extends('layouts.app')

@section('title', 'Order Details')

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
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-light rounded-pill me-3 px-4">
                    <i class="bi bi-arrow-left me-2"></i>Back to Orders
                </a>
                <div>
                    <h1 class="h3 fw-bold mb-0 text-primary">Order Details</h1>
                    <p class="text-muted mb-0">Order ID: <strong>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong></p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-8">
                    <div class="card shadow-sm border-0 rounded-4 p-4 mb-4">
                        <h5 class="fw-bold mb-4">Purchased Items</h5>
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle">
                                <thead class="border-bottom">
                                    <tr>
                                        <th class="ps-0 text-muted small fw-bold text-uppercase">Item</th>
                                        <th class="text-center text-muted small fw-bold text-uppercase">Qty</th>
                                        <th class="text-end text-muted small fw-bold text-uppercase">Price</th>
                                        <th class="text-end pe-0 text-muted small fw-bold text-uppercase">Vouchers</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr class="border-bottom">
                                            <td class="ps-0 py-3">
                                                <div class="fw-bold">{{ $item->product->name ?? $item->product_name }}</div>
                                                <div class="small text-muted">{{ $item->product->store->name ?? 'Unknown Store' }}</div>
                                            </td>
                                            <td class="text-center py-3">
                                                x{{ $item->quantity }}
                                            </td>
                                            <td class="text-end fw-bold text-primary py-3">
                                                ₱{{ number_format($item->price * $item->quantity, 2) }}
                                                @if($item->quantity > 1)
                                                    <div class="small text-muted fw-normal">₱{{ number_format($item->price, 2) }} each</div>
                                                @endif
                                            </td>
                                            <td class="text-end pe-0 py-3">
                                                @foreach($order->vouchers->where('product_id', $item->product_id) as $voucher)
                                                    <div class="mb-1">
                                                        <span class="badge bg-light text-dark border rounded-pill">Voucher: #{{ str_pad($voucher->id, 6, '0', STR_PAD_LEFT) }}</span>
                                                    </div>
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="ps-0 pt-4 fw-bold">Total Amount</td>
                                        <td colspan="2" class="text-end pe-0 pt-4 fw-bold text-primary h5 mb-0">
                                            ₱{{ number_format($order->total_amount, 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 rounded-4 p-4 mb-4">
                        <h5 class="fw-bold mb-3">Order Status</h5>
                        <div class="mb-2">
                            @if($order->status === 'paid')
                                <span class="badge bg-success rounded-pill px-3 py-2 w-100 text-start fs-6"><i class="bi bi-check-circle me-2"></i>Paid</span>
                            @elseif($order->status === 'pending')
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2 w-100 text-start fs-6"><i class="bi bi-clock me-2"></i>Pending</span>
                            @else
                                <span class="badge bg-danger rounded-pill px-3 py-2 w-100 text-start fs-6"><i class="bi bi-x-circle me-2"></i>{{ ucfirst($order->status) }}</span>
                            @endif
                        </div>
                        <div class="small text-muted mt-3">
                            <div><strong>Created:</strong> {{ $order->created_at->format('M d, Y h:i A') }}</div>
                            @if($order->hitpay_transaction_id)
                                <div class="mt-2"><strong>HitPay ID:</strong><br><span class="text-break">{{ $order->hitpay_transaction_id }}</span></div>
                            @endif
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 rounded-4 p-4">
                        <h5 class="fw-bold mb-3">Gifter Details</h5>
                        @if($order->gifter)
                            <div class="mb-2">
                                <div class="fw-bold">{{ $order->gifter->name ?? 'Guest User' }}</div>
                                <div class="text-muted">{{ $order->gifter->email }}</div>
                            </div>
                        @else
                            <div class="text-muted fst-italic">Unknown / Guest</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection