@extends('layouts.app')

@section('title', 'Manage Refunds (Admin)')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            @include('admin.partials.menu')
        </div>
        <div class="col-md-9">
            <h1 class="h3 fw-bold mb-4 text-primary">Refund Management</h1>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-pill px-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-pill px-4" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Voucher / Order</th>
                                    <th>Gifter</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Requested At</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($refunds as $voucher)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold small">{{ $voucher->unique_token }}</div>
                                            <div class="small text-muted">{{ $voucher->product->name }}</div>
                                            <div class="small text-muted">Order #{{ $voucher->order_id }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-bold small">{{ $voucher->order->gifter->name }}</div>
                                            <div class="small text-muted">{{ $voucher->order->gifter->email }}</div>
                                        </td>
                                        <td>₱{{ number_format($voucher->price, 2) }}</td>
                                        <td>
                                            @if($voucher->status === 'refund_pending')
                                                <span class="badge bg-warning text-dark rounded-pill">Pending</span>
                                            @elseif($voucher->status === 'refunded')
                                                <span class="badge bg-success rounded-pill">Refunded</span>
                                            @endif
                                        </td>
                                        <td class="small">{{ $voucher->refund_requested_at ? $voucher->refund_requested_at->format('M d, Y h:i A') : 'N/A' }}</td>
                                        <td class="text-end pe-4">
                                            @if($voucher->status === 'refund_pending')
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-light rounded-pill dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        Process
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                                        <li>
                                                            <button class="dropdown-item fw-bold" data-bs-toggle="modal" data-bs-target="#reasonModal{{ $voucher->id }}">
                                                                View Reason
                                                            </button>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form action="{{ route('admin.refunds.approve', $voucher) }}" method="POST" onsubmit="return confirm('Approve this refund?')">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item text-success fw-bold">Approve Refund</button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('admin.refunds.reject', $voucher) }}" method="POST" onsubmit="return confirm('Reject this refund request?')">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item text-danger">Reject Request</button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <!-- Reason Modal -->
                                                <div class="modal fade" id="reasonModal{{ $voucher->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered text-start">
                                                        <div class="modal-content border-0 rounded-4">
                                                            <div class="modal-header border-0 pb-0">
                                                                <h5 class="modal-title fw-bold">Refund Reason</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body p-4">
                                                                <div class="bg-light p-3 rounded-3 mb-3">
                                                                    {{ $voucher->refund_reason }}
                                                                </div>
                                                                <div class="small text-muted">
                                                                    Voucher ID: <code>{{ $voucher->unique_token }}</code>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted small">Completed</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">No refund requests found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                {{ $refunds->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
