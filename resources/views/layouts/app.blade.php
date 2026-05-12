<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Beam Gifts')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">Beam Gifts</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Home</a>
                    </li>
                    
                    @if(app()->has('current_city'))
                        <li class="nav-item">
                            <span class="nav-link text-muted">| City: <span class="fw-bold text-dark">{{ app('current_city')->name }}</span></span>
                        </li>
                        <li class="nav-item ms-lg-2">
                            <a href="{{ route('cart.index', ['city_slug' => app('current_city')->slug]) }}" class="nav-link position-relative">
                                <i class="bi bi-cart3 fs-5"></i>
                                @php $cartCount = count(Session::get('cart_'.app('current_city')->id, [])); @endphp
                                <span id="cartCountBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary border border-light {{ $cartCount > 0 ? '' : 'd-none' }}" style="font-size: 0.6rem;">
                                    {{ $cartCount }}
                                </span>
                            </a>
                        </li>
                    @endif

                    <li class="nav-item ms-lg-3">
                        @if(Auth::guard('admin')->check())
                            <div class="dropdown">
                                <a class="btn btn-outline-primary rounded-pill dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    Admin: {{ Auth::guard('admin')->user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @elseif(Auth::guard('partner')->check())
                            <div class="dropdown">
                                <a class="btn btn-outline-primary rounded-pill dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    Partner: {{ Auth::guard('partner')->user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                    <li><a class="dropdown-item" href="{{ route('partner.dashboard') }}">Dashboard</a></li>
                                    <li><a class="dropdown-item" href="{{ route('partner.managers.index') }}">Branch Managers</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('partner.logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @elseif(Auth::guard('manager')->check())
                            <div class="dropdown">
                                <a class="btn btn-outline-primary rounded-pill dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    Manager: {{ Auth::guard('manager')->user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                    <li><a class="dropdown-item" href="{{ route('manager.vouchers.scan') }}">QR Scanner</a></li>
                                    <li><a class="dropdown-item" href="{{ route('manager.vouchers.transactions') }}">Transactions</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('manager.logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @elseif(Auth::guard('web')->check())
                            <div class="dropdown">
                                <a class="btn btn-outline-primary rounded-pill dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    {{ Auth::guard('web')->user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('profile') }}">Profile</a></li>
                                    <li><a class="dropdown-item" href="{{ route('my-gifts') }}">My Gifts</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <a class="btn btn-primary text-white rounded-pill px-4" href="{{ route('login') }}">
                                Login
                            </a>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <!-- Mobile Bottom Navigation Bar -->
    <nav class="mobile-bottom-nav border-top">
        @if(Auth::guard('partner')->check())
            <div class="nav-item">
                <a href="{{ route('partner.dashboard') }}" class="nav-link {{ Request::routeIs('partner.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('partner.vouchers.scan') }}" class="nav-link {{ Request::routeIs('partner.vouchers.scan') ? 'active' : '' }}">
                    <i class="bi bi-qr-code-scan"></i>
                    <span>Scan</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('partner.managers.index') }}" class="nav-link {{ Request::routeIs('partner.managers.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>Managers</span>
                </a>
            </div>
        @elseif(Auth::guard('manager')->check())
            <div class="nav-item">
                <a href="{{ route('manager.vouchers.scan') }}" class="nav-link {{ Request::routeIs('manager.vouchers.scan') ? 'active' : '' }}">
                    <i class="bi bi-qr-code-scan"></i>
                    <span>Scan</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('manager.vouchers.transactions') }}" class="nav-link {{ Request::routeIs('manager.vouchers.transactions') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i>
                    <span>History</span>
                </a>
            </div>
            <div class="nav-item">
                <form action="{{ route('manager.logout') }}" method="POST" id="managerLogoutForm" class="d-none">@csrf</form>
                <a href="javascript:void(0)" onclick="document.getElementById('managerLogoutForm').submit()" class="nav-link text-danger">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </div>
        @else
            <div class="nav-item">
                <a href="{{ url('/') }}" class="nav-link {{ Request::is('/') ? 'active' : '' }}">
                    <i class="bi bi-shop"></i>
                    <span>Home</span>
                </a>
            </div>
            @if(app()->has('current_city'))
                <div class="nav-item">
                    <a href="{{ route('cart.index', ['city_slug' => app('current_city')->slug]) }}" class="nav-link {{ Request::routeIs('cart.index') ? 'active' : '' }} position-relative">
                        <i class="bi bi-cart3"></i>
                        <span>Cart</span>
                        @php $cartCount = count(Session::get('cart_'.app('current_city')->id, [])); @endphp
                        <span id="mobileCartCountBadge" class="position-absolute badge rounded-pill bg-primary border border-light {{ $cartCount > 0 ? '' : 'd-none' }}">
                            {{ $cartCount }}
                        </span>
                    </a>
                </div>
            @endif
            <div class="nav-item">
                @if(Auth::guard('web')->check())
                    <a href="{{ route('profile') }}" class="nav-link {{ Request::routeIs('profile', 'my-gifts', 'my-orders') ? 'active' : '' }}">
                        <i class="bi bi-person-circle"></i>
                        <span>Account</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="nav-link {{ Request::routeIs('login') ? 'active' : '' }}">
                        <i class="bi bi-person-circle"></i>
                        <span>Login</span>
                    </a>
                @endif
            </div>
        @endif
    </nav>

    <footer class="bg-light py-5 mt-5 border-top">
        <div class="container text-center">
            <h4 class="fw-bold text-primary mb-3">Beam Gifts</h4>
            <p class="text-muted mb-4">Sharing joy, one gift at a time.</p>
            <div class="mb-4">
                <a href="{{ route('page.about') }}" class="text-muted text-decoration-none mx-2 small">About Us</a>
                <a href="{{ route('page.terms') }}" class="text-muted text-decoration-none mx-2 small">Terms of Service</a>
                <a href="{{ route('page.privacy') }}" class="text-muted text-decoration-none mx-2 small">Privacy Policy</a>
            </div>
            <p class="text-muted small mb-0">&copy; {{ date('Y') }} Beam Gifts. All rights reserved.</p>
        </div>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Store original button classes for restoration
            document.querySelectorAll('.add-to-cart-form button[type="submit"]').forEach(btn => {
                btn.setAttribute('data-original-class', btn.className);
            });
        });

        document.addEventListener('submit', async function(e) {
            if (e.target && e.target.matches('.add-to-cart-form')) {
                e.preventDefault();
                const form = e.target;
                const btn = form.querySelector('button[type="submit"]');
                const originalContent = btn.innerHTML;
                const originalClass = btn.getAttribute('data-original-class') || btn.className;

                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...';

                try {
                    const response = await fetch(form.action, {
                        method: form.method,
                        body: new FormData(form),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Update both desktop and mobile badges
                        const desktopBadge = document.getElementById('cartCountBadge');
                        const mobileBadge = document.getElementById('mobileCartCountBadge');
                        
                        [desktopBadge, mobileBadge].forEach(badge => {
                            if (badge) {
                                badge.innerText = data.cartCount;
                                badge.classList.remove('d-none');
                            }
                        });
                        
                        // Visual feedback
                        btn.innerHTML = '<i class="bi bi-check2"></i> Added!';
                        btn.className = 'btn btn-success rounded-pill w-100'; // Standardize for feedback
                        if (btn.classList.contains('btn-sm')) btn.classList.add('btn-sm');

                        setTimeout(() => {
                            btn.disabled = false;
                            btn.innerHTML = originalContent;
                            btn.className = originalClass;
                        }, 2000);
                    } else {
                        alert(data.message || 'Error adding to cart');
                        btn.disabled = false;
                        btn.innerHTML = originalContent;
                    }
                } catch (error) {
                    console.error('Error:', error);
                    btn.disabled = false;
                    btn.innerHTML = originalContent;
                }
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
