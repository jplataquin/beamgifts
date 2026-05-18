@extends('layouts.app')

@section('title', 'Platform Orders')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            @include('admin.partials.menu')
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
                            <input type="text" name="search" class="form-control rounded-pill" placeholder="Search by Order ID or HitPay ID..." value="{{ request('search') }}">
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
                                <th class="ps-4 py-3">Order ID</th>
                                <th class="py-3">Gifter</th>
                                <th class="py-3">Partner</th>
                                <th class="py-3">Date</th>
                                <th class="py-3">Amount</th>
                                <th class="py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody id="orders-table-body">
                            @include('admin.orders._rows')
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div id="load-more-container" class="mt-4 text-center {{ !$orders->hasMorePages() ? 'd-none' : '' }}">
                <button id="load-more-btn" class="btn btn-outline-primary rounded-pill px-5 py-2 fw-bold" data-next-page="{{ $orders->currentPage() + 1 }}">
                    Show More Orders
                </button>
            </div>
            <div id="loading-spinner" class="mt-4 text-center d-none">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loadMoreBtn = document.getElementById('load-more-btn');
        const container = document.getElementById('load-more-container');
        const spinner = document.getElementById('loading-spinner');
        const tableBody = document.getElementById('orders-table-body');

        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function() {
                const nextPage = this.getAttribute('data-next-page');
                if (!nextPage) return;

                // Show spinner, hide button
                container.classList.add('d-none');
                spinner.classList.remove('d-none');

                // Prepare URL with current filters
                const url = new URL(window.location.href);
                url.searchParams.set('page', nextPage);

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    const hasMore = response.headers.get('X-Has-More-Pages') === '1';
                    return response.text().then(html => ({ html, hasMore }));
                })
                .then(({ html, hasMore }) => {
                    if (html.trim().length > 0) {
                        tableBody.insertAdjacentHTML('beforeend', html);
                        
                        const currentNextPage = parseInt(nextPage);
                        this.setAttribute('data-next-page', currentNextPage + 1);
                        
                        spinner.classList.add('d-none');
                        if (hasMore) {
                            container.classList.remove('d-none');
                        } else {
                            container.classList.add('d-none');
                        }
                    } else {
                        spinner.classList.add('d-none');
                        container.classList.add('d-none');
                    }
                })
                .catch(error => {
                    console.error('Error loading more orders:', error);
                    spinner.classList.add('d-none');
                    container.classList.remove('d-none');
                });
            });
        }
    });
</script>
@endpush
@endsection