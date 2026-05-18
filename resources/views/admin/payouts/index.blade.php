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

            <ul class="nav nav-pills mb-4" id="payoutTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill px-4 me-2" id="eligible-tab" data-bs-toggle="pill" data-bs-target="#eligible" type="button" role="tab">
                        Eligible Vouchers ({{ $eligibleVouchers->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-4" id="flagged-tab" data-bs-toggle="pill" data-bs-target="#flagged" type="button" role="tab">
                        Flagged for Payout ({{ $flaggedVouchers->flatten()->count() }})
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="payoutTabsContent">
                <!-- Eligible Tab -->
                <div class="tab-pane fade show active" id="eligible" role="tabpanel">
                    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                        <div class="card-body p-0">
                            <form action="{{ route('admin.payouts.tag') }}" method="POST" id="tagForm">
                                @csrf
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
                                                    <td colspan="5" class="text-center py-5 text-muted">No eligible vouchers found for tagging.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                @if($eligibleVouchers->count() > 0)
                                    <div class="p-4 bg-light border-top text-end">
                                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold" id="tagBtn" disabled>
                                            Tag Selected for Payout
                                        </button>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Flagged Tab -->
                <div class="tab-pane fade" id="flagged" role="tabpanel">
                    @forelse($flaggedVouchers as $partnerId => $vouchers)
                        @php 
                            $firstVoucher = $vouchers->first();
                            $partner = $firstVoucher->product->store->partner ?? null;
                            $businessName = $partner->business_name ?? ($firstVoucher->product->store->name ?? 'Unknown Partner');
                        @endphp
                        <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden">
                            <div class="card-header bg-white py-3 ps-4 d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0 fw-bold">{{ $businessName }}</h5>
                                    @if($partner)
                                        <span class="text-muted small">{{ $partner->name }} ({{ $partner->email }})</span>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <div class="small text-muted mb-1">{{ $vouchers->count() }} vouchers</div>
                                    <div class="h5 mb-0 fw-bold text-primary">
                                        ₱{{ number_format($vouchers->sum(fn($v) => $v->price ?? ($v->product->price ?? 0)), 2) }}
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="ps-4 py-2">ID</th>
                                                <th class="py-2">Product</th>
                                                <th class="py-2">Claimed</th>
                                                <th class="py-2 pe-4 text-end">Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($vouchers as $voucher)
                                                <tr>
                                                    <td class="ps-4"><code>#{{ str_pad($voucher->id, 6, '0', STR_PAD_LEFT) }}</code></td>
                                                    <td>{{ $voucher->product->name ?? 'Unknown' }}</td>
                                                    <td class="text-muted small">{{ $voucher->claimed_at ? $voucher->claimed_at->format('M d, Y') : 'N/A' }}</td>
                                                    <td class="pe-4 text-end">₱{{ number_format($voucher->price ?? ($voucher->product->price ?? 0), 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="p-3 bg-light border-top text-center">
                                    <button class="btn btn-outline-success btn-sm rounded-pill px-4 fw-bold">
                                        Generate Payout ID for {{ $businessName }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card shadow-sm border-0 rounded-4 p-5 text-center text-muted">
                            No vouchers currently tagged for payout.
                        </div>
                    @endforelse
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
        const tagBtn = document.getElementById('tagBtn');

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                updateTagBtn();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateTagBtn);
        });

        function updateTagBtn() {
            const checkedCount = document.querySelectorAll('.voucher-checkbox:checked').length;
            if (tagBtn) {
                tagBtn.disabled = checkedCount === 0;
                tagBtn.textContent = checkedCount > 0 ? `Tag ${checkedCount} selected for Payout` : 'Tag Selected for Payout';
            }
        }
    });
</script>
@endpush
@endsection
