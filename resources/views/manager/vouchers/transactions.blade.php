@extends('layouts.app')

@section('title', 'Branch Transactions')

@section('content')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .print-section, .print-section * {
            visibility: visible;
        }
        .print-section {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>

<div class="container py-5 print-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-0 text-primary">Redemption History</h1>
            <p class="text-muted">History of vouchers claimed at <strong>{{ Auth::guard('partner')->user()->branch->name }}</strong>.</p>
        </div>
        <button onclick="window.print()" class="btn btn-outline-secondary rounded-pill px-4 no-print">
            <i class="bi bi-printer me-2"></i>Print
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-pill px-4 mb-4 border-0 shadow-sm no-print">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4 no-print">
        <div class="card-body">
            <form action="{{ route('manager.vouchers.transactions') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Status</label>
                    <select name="status" class="form-select rounded-pill">
                        <option value="">All Statuses</option>
                        <option value="claimed" {{ request('status') === 'claimed' ? 'selected' : '' }}>Claimed</option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">From Date (Claimed)</label>
                    <input type="date" name="from_date" class="form-control rounded-pill" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">To Date (Claimed)</label>
                    <input type="date" name="to_date" class="form-control rounded-pill" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary rounded-pill w-100">Filter</button>
                    <a href="{{ route('manager.vouchers.transactions') }}" class="btn btn-light rounded-pill ms-2">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Redeemed At</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Product</th>
                        <th class="py-3">Price</th>
                        <th class="py-3">Processed By</th>
                        <th class="py-3">Voucher ID</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vouchers as $voucher)
                        <tr>
                            <td class="ps-4">
                                @if($voucher->claimed_at)
                                    <div class="fw-bold text-dark">{{ $voucher->claimed_at->format('M d, Y') }}</div>
                                    <div class="small text-muted">{{ $voucher->claimed_at->format('h:i A') }}</div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($voucher->status === 'claimed')
                                    <span class="badge bg-secondary rounded-pill">Claimed</span>
                                @elseif($voucher->status === 'expired')
                                    <span class="badge bg-danger rounded-pill">Expired</span>
                                @else
                                    <span class="badge bg-success rounded-pill">{{ ucfirst($voucher->status) }}</span>
                                @endif
                            </td>
                            <td class="fw-bold">{{ $voucher->product->name }}</td>
                            <td class="fw-bold text-primary">₱{{ number_format($voucher->price ?? $voucher->product->price, 2) }}</td>
                            <td>{{ $voucher->claimedByUser ? $voucher->claimedByUser->name : 'N/A' }}</td>
                            <td><code>#{{ str_pad($voucher->id, 6, '0', STR_PAD_LEFT) }}</code></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No transactions recorded matching your criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 no-print">
        {{ $vouchers->links() }}
    </div>
</div>
@endsection