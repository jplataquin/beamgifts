@extends('layouts.app')

@section('title', 'Manage Products')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            @include('partner.partials.menu')
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 fw-bold mb-0 text-primary">Manage Products</h1>
                <a href="{{ route('partner.products.create') }}" class="btn btn-primary rounded-pill px-4">Add New Product</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-pill px-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Product</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th class="pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr class="clickable-row" data-href="{{ route('partner.products.show', $product) }}" style="cursor: pointer;">
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                @if(!empty($product->images))
                                                    <img src="{{ Storage::url($product->images[0]) }}" class="rounded me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <span class="text-muted small">N/A</span>
                                                    </div>
                                                @endif
                                                <div class="fw-bold">{{ $product->name }}</div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-light text-dark rounded-pill">{{ $product->category }}</span></td>
                                        <td class="fw-bold text-primary">₱{{ number_format($product->price, 2) }}</td>
                                        <td class="pe-4">
                                            @if($product->is_banned)
                                                <span class="badge bg-danger rounded-pill">Banned</span>
                                            @elseif($product->status === 'Inactive')
                                                <span class="badge bg-secondary rounded-pill">Inactive</span>
                                            @else
                                                <span class="badge bg-success rounded-pill">Active</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">No products found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('.clickable-row');
        rows.forEach(row => {
            row.addEventListener('click', function(e) {
                // Redirect to the data-href unless a link or button was clicked
                if (e.target.tagName !== 'A' && e.target.tagName !== 'BUTTON' && !e.target.closest('a') && !e.target.closest('button')) {
                    window.location.href = this.dataset.href;
                }
            });
        });
    });
</script>
@endpush
@endsection
