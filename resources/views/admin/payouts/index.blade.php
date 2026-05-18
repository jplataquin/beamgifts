@extends('layouts.app')

@section('title', 'Payout Management')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            @include('admin.partials.menu')
        </div>
        <div class="col-md-9">
            <h1 class="h3 fw-bold mb-4 text-primary">Payout Management</h1>

            @if(session('success'))
                <div class="alert alert-success rounded-pill px-4 mb-4 border-0 shadow-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger rounded-pill px-4 mb-4 border-0 shadow-sm">{{ session('error') }}</div>
            @endif

            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-body p-4">
                    <form action="{{ route('admin.payouts.index') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Filter by Partner</label>
                            <select name="partner_id" class="form-select rounded-pill">
                                <option value="">All Partners</option>
                                @foreach($partners as $partner)
                                    <option value="{{ $partner->id }}" {{ request('partner_id') == $partner->id ? 'selected' : '' }}>
                                        {{ $partner->business_name }} ({{ $partner->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted">From Date (Claimed)</label>
                            <input type="date" name="from_date" class="form-control rounded-pill" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted">To Date (Claimed)</label>
                            <input type="date" name="to_date" class="form-control rounded-pill" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary rounded-pill w-100 fw-bold me-2">Filter</button>
                            <a href="{{ route('admin.payouts.index') }}" class="btn btn-light rounded-pill">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <ul class="nav nav-pills mb-4" id="payoutTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill px-4 me-2" id="eligible-tab" data-bs-toggle="pill" data-bs-target="#eligible" type="button" role="tab">
                        Eligible Vouchers ({{ $eligibleVouchers->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-4" id="orders-tab" data-bs-toggle="pill" data-bs-target="#orders" type="button" role="tab">
                        Payout Orders ({{ $payouts->count() }})
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="payoutTabsContent">
                <!-- Eligible Tab -->
                <div class="tab-pane fade show active" id="eligible" role="tabpanel">
                    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                        <div class="card-body p-0">
                            <form action="{{ route('admin.payouts.store') }}" method="POST" id="payoutForm">
                                @csrf
                                <input type="hidden" name="from_date" value="{{ request('from_date') }}">
                                <input type="hidden" name="to_date" value="{{ request('to_date') }}">
                                
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="ps-4 py-3" style="width: 40px;">
                                                    <input type="checkbox" class="form-check-input" id="selectAll">
                                                </th>
                                                <th class="py-3">Voucher ID</th>
                                                <th class="py-3">Product & Partner</th>
                                                <th class="py-3">Claimed Date</th>
                                                <th class="py-3 pe-4 text-end">Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($eligibleVouchers as $voucher)
                                                <tr>
                                                    <td class="ps-4">
                                                        <input type="checkbox" name="voucher_ids[]" value="{{ $voucher->id }}" class="form-check-input voucher-checkbox">
                                                    </td>
                                                    <td><code>#{{ str_pad($voucher->id, 6, '0', STR_PAD_LEFT) }}</code></td>
                                                    <td>
                                                        <div class="fw-bold">{{ $voucher->product->name ?? 'Unknown' }}</div>
                                                        <div class="small text-muted">{{ $voucher->product->store->name ?? 'Unknown Store' }}</div>
                                                        <div class="small text-primary fw-bold">{{ $voucher->product->store->owner->business_name ?? 'N/A' }}</div>
                                                    </td>
                                                    <td class="text-muted">
                                                        {{ $voucher->claimed_at ? $voucher->claimed_at->format('M d, Y') : 'N/A' }}
                                                    </td>
                                                    <td class="pe-4 text-end fw-bold text-primary">
                                                        ₱{{ number_format($voucher->price ?? ($voucher->product->price ?? 0), 2) }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-5 text-muted">No eligible vouchers found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                @if($eligibleVouchers->count() > 0)
                                    <div class="p-4 bg-light border-top text-end">
                                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold" id="generateBtn" disabled>
                                            Generate Payout
                                        </button>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Payout Orders Tab -->
                <div class="tab-pane fade" id="orders" role="tabpanel">
                    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4 py-3">Payout ID</th>
                                            <th class="py-3">Partner</th>
                                            <th class="py-3 text-center">Period</th>
                                            <th class="py-3 text-center">Status</th>
                                            <th class="py-3 text-center">Vouchers</th>
                                            <th class="py-3 text-end pe-4">Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($payouts as $payout)
                                            <tr>
                                                <td class="ps-4">
                                                    <span class="badge bg-light text-dark border rounded-pill px-3">
                                                        #{{ str_pad($payout->id, 6, '0', STR_PAD_LEFT) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="fw-bold">{{ $payout->partner->business_name ?? 'N/A' }}</div>
                                                    <div class="small text-muted">{{ $payout->partner->name ?? 'N/A' }}</div>
                                                </td>
                                                <td class="text-center small">
                                                    <div>{{ $payout->from ? $payout->from->format('M d, Y') : 'N/A' }}</div>
                                                    <div class="text-muted">to</div>
                                                    <div>{{ $payout->to ? $payout->to->format('M d, Y') : 'N/A' }}</div>
                                                </td>
                                                <td class="text-center">
                                                    @if($payout->status == 'pending')
                                                        <span class="badge bg-warning rounded-pill px-3">Pending</span>
                                                    @elseif($payout->status == 'completed')
                                                        <span class="badge bg-success rounded-pill px-3">Completed</span>
                                                    @else
                                                        <span class="badge bg-secondary rounded-pill px-3">{{ ucfirst($payout->status) }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center fw-bold">
                                                    {{ $payout->vouchers_count ?? $payout->vouchers->count() }}
                                                </td>
                                                <td class="text-end pe-4 fw-bold text-primary">
                                                    ₱{{ number_format($payout->total_amount, 2) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-5 text-muted">No payout orders found.</td>
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
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.voucher-checkbox');
        const generateBtn = document.getElementById('generateBtn');

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                updateGenerateBtn();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateGenerateBtn);
        });

        function updateGenerateBtn() {
            const checkedCount = document.querySelectorAll('.voucher-checkbox:checked').length;
            if (generateBtn) {
                generateBtn.disabled = checkedCount === 0;
                generateBtn.textContent = checkedCount > 0 ? `Generate Payout for ${checkedCount} items` : 'Generate Payout';
            }
        }
    });
</script>
@endpush
@endsection
