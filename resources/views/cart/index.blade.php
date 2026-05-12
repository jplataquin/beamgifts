@extends('layouts.app')

@section('title', 'Shopping Cart - Beam Gifts')

@section('content')
<div class="container py-5">
    <h1 class="h3 fw-bold mb-4 text-primary">Your Shopping Cart in {{ $city->name }}</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-pill px-4 mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(empty($cart))
        <div class="card shadow-sm border-0 p-5 text-center">
            <div class="card-body">
                <i class="bi bi-cart-x display-1 text-muted mb-4"></i>
                <h2 class="h4 fw-bold">Your cart is empty</h2>
                <p class="text-muted mb-4">You haven't added any gifts from {{ $city->name }} yet.</p>
                <a href="{{ route('city.home', ['city_slug' => $city->slug]) }}" class="btn btn-primary rounded-pill px-5 py-2">Explore Gifts</a>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 overflow-hidden mb-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Product</th>
                                        <th>Price</th>
                                        <th style="width: 120px;">Quantity</th>
                                        <th>Subtotal</th>
                                        <th class="text-end pe-4"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart as $id => $item)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    @if($item['image'])
                                                        <img src="{{ Storage::url($item['image']) }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded me-3" style="width: 60px; height: 60px;"></div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold text-dark">{{ $item['name'] }}</div>
                                                        <div class="small text-muted">{{ $item['store_name'] }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>₱{{ number_format($item['price'], 2) }}</td>
                                            <td>
                                                <form action="{{ route('cart.update', ['city_slug' => $city->slug, 'productId' => $id]) }}" method="POST" class="update-cart-form">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="3" 
                                                        class="form-control form-control-sm rounded-pill text-center quantity-input" 
                                                        data-product-id="{{ $id }}" 
                                                        data-price="{{ $item['price'] }}">
                                                    <div class="text-muted mt-1" style="font-size: 0.6rem;">Max: 3</div>
                                                </form>
                                            </td>
                                            <td class="fw-bold"><span id="subtotal-{{ $id }}">₱{{ number_format($item['price'] * $item['quantity'], 2) }}</span></td>
                                            <td class="text-end pe-4">
                                                <form action="{{ route('cart.remove', ['city_slug' => $city->slug, 'productId' => $id]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <a href="{{ route('city.home', ['city_slug' => $city->slug]) }}" class="btn btn-light rounded-pill px-4">
                    &larr; Continue Shopping
                </a>
            </div>
            
            <div class="col-md-4">
                <div class="card shadow-sm border-0 p-4" id="order-summary-card" data-markup-percent="{{ \App\Models\Setting::get('global_markup_percentage', 0) }}">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">Order Summary</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span id="cart-subtotal">₱{{ number_format($total, 2) }}</span>
                        </div>
                        @php 
                            $markupPercent = \App\Models\Setting::get('global_markup_percentage', 0);
                            $markupAmount = ($total * $markupPercent) / 100;
                            $grandTotal = $total + $markupAmount;
                        @endphp
                        <div class="d-flex justify-content-between mb-4">
                            <span class="text-muted">Markup/Fees ({{ $markupPercent }}%)</span>
                            <span id="cart-markup" class="text-muted">₱{{ number_format($markupAmount, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-5">
                            <span class="h5 fw-bold">Total</span>
                            <span id="cart-total" class="h5 fw-bold text-primary">₱{{ number_format($grandTotal, 2) }}</span>
                        </div>
                        
                        @if(Auth::guard('web')->check())
                            <form action="{{ route('checkout.store', ['city_slug' => $city->slug]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm">Proceed to Payment</button>
                            </form>
                        @else
                            <div class="alert alert-info small rounded-4 border-0">
                                Please <a href="{{ route('login') }}" class="fw-bold">Login</a> or <a href="{{ route('register') }}" class="fw-bold">Register</a> to proceed with the checkout.
                            </div>
                            <button disabled class="btn btn-secondary w-100 rounded-pill py-3 fw-bold opacity-50">Proceed to Payment</button>
                        @endif
                        
                        <div class="text-center mt-4">
                            <img src="https://www.hit-pay.com/static/media/hitpay-logo.e263e68e.svg" style="height: 25px;" class="opacity-50" alt="HitPay">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Toast Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
  <div id="cartToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body" id="toastMessage">
        Maximum quantity reached.
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Wait for bootstrap to be available (since it's loaded as a module)
    const initCart = () => {
        if (typeof bootstrap === 'undefined') {
            setTimeout(initCart, 100);
            return;
        }

        const quantityInputs = document.querySelectorAll('.quantity-input');
        const toastEl = document.getElementById('cartToast');
        const toast = new bootstrap.Toast(toastEl);
        const toastMessage = document.getElementById('toastMessage');

        function showToast(message, type = 'danger') {
            toastEl.classList.remove('bg-danger', 'bg-success', 'bg-warning', 'bg-info');
            toastEl.classList.add('bg-' + type);
            toastMessage.innerText = message;
            toast.show();
        }

        function updateClientSideTotals() {
            let totalSubtotal = 0;
            const summaryCard = document.getElementById('order-summary-card');
            if (!summaryCard) return;
            
            const markupPercent = parseFloat(summaryCard.dataset.markupPercent);
            
            document.querySelectorAll('.quantity-input').forEach(inp => {
                const itemPrice = parseFloat(inp.dataset.price);
                const itemQty = parseInt(inp.value);
                const itemSubtotal = itemPrice * itemQty;
                
                const subtotalEl = document.getElementById(`subtotal-${inp.dataset.productId}`);
                if (subtotalEl) {
                    subtotalEl.innerText = '₱' + itemSubtotal.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                }
                totalSubtotal += itemSubtotal;
            });

            const markupAmount = (totalSubtotal * markupPercent) / 100;
            const grandTotal = totalSubtotal + markupAmount;

            document.getElementById('cart-subtotal').innerText = '₱' + totalSubtotal.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('cart-markup').innerText = '₱' + markupAmount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('cart-total').innerText = '₱' + grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }

        quantityInputs.forEach(input => {
            // Handle instant client-side updates and background sync
            input.addEventListener('change', async function() {
                const form = this.closest('form');
                let val = parseInt(this.value);
                
                if (val > 3) {
                    this.value = 3;
                    val = 3;
                    showToast('Quantity capped at 3.', 'warning');
                }
                if (val < 1) {
                    this.value = 1;
                    val = 1;
                }

                // 1. Instant update
                updateClientSideTotals();

                // 2. Background sync
                try {
                    await fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                } catch (error) {
                    console.error('Sync Error:', error);
                }
            });

            // Strict keyboard handling
            input.addEventListener('keydown', function(e) {
                if (['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight'].includes(e.code)) return;
                
                if (!/^[0-9]$/.test(e.key)) {
                    e.preventDefault();
                    return;
                }
                
                const currentValue = this.value;
                const selectionStart = this.selectionStart;
                const selectionEnd = this.selectionEnd;
                const newValue = currentValue.substring(0, selectionStart) + e.key + currentValue.substring(selectionEnd);
                
                const numValue = parseInt(newValue);
                if (numValue > 3) {
                    e.preventDefault();
                    showToast('Maximum quantity is 3 per item.', 'warning');
                } else if (numValue < 1) {
                    e.preventDefault();
                }
            });

            // Strict paste handling
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pasteData = (e.clipboardData || window.clipboardData).getData('text');
                const numValue = parseInt(pasteData);
                
                if (!isNaN(numValue) && numValue >= 1 && numValue <= 3) {
                    this.value = numValue;
                    this.dispatchEvent(new Event('change'));
                } else {
                    showToast('Please enter a quantity between 1 and 3.', 'warning');
                }
            });
        });
    };

    initCart();
});
</script>
@endpush
