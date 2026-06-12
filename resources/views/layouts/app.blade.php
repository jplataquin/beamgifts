<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Gift-XP')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
@if(!(request()->is('admin*') || request()->is('partner*') || request()->is('manager*')))
    <!-- Cozy Gifting Fonts & Theme Overrides -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Syne:wght@700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* Warm Gifting Theme Globals */
        :root {
            --bg-light-cozy: #FAF6F0;
            --accent-coral: #E76F51;
            --accent-peach: #F4A261;
            --accent-sage: #8A9A86;
            --accent-gold: #E9C46A;
            --text-dark-espresso: #3D3430;
        }

        /* Scope everything under .cozy-theme-active to prevent touching Admin/Partner dashboards */
        body.cozy-theme-active {
            background-color: var(--bg-light-cozy) !important;
            color: var(--text-dark-espresso) !important;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-image: radial-gradient(circle at 100% 0%, rgba(244, 162, 97, 0.05) 0%, transparent 40%),
                              radial-gradient(circle at 0% 100%, rgba(138, 154, 134, 0.05) 0%, transparent 40%);
            background-attachment: fixed;
        }

        /* Typography */
        body.cozy-theme-active h1, 
        body.cozy-theme-active h2, 
        body.cozy-theme-active h3, 
        body.cozy-theme-active h4, 
        body.cozy-theme-active h5, 
        body.cozy-theme-active h6,
        body.cozy-theme-active .display-1,
        body.cozy-theme-active .display-2,
        body.cozy-theme-active .display-3,
        body.cozy-theme-active .display-4 {
            color: var(--text-dark-espresso) !important;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
        }

        body.cozy-theme-active p, 
        body.cozy-theme-active span,
        body.cozy-theme-active li, 
        body.cozy-theme-active div,
        body.cozy-theme-active label {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body.cozy-theme-active .text-primary {
            color: var(--accent-coral) !important;
        }
        body.cozy-theme-active .text-muted {
            color: #7A6F69 !important; /* Soft warm grey */
        }
        body.cozy-theme-active .text-dark {
            color: var(--text-dark-espresso) !important;
        }

        /* Frosted Cozy Navbar */
        body.cozy-theme-active .navbar {
            background: rgba(250, 246, 240, 0.8) !important;
            backdrop-filter: blur(20px) saturate(180%) !important;
            -webkit-backdrop-filter: blur(20px) saturate(180%) !important;
            border-bottom: 1px solid rgba(61, 52, 48, 0.04) !important;
            box-shadow: 0 4px 30px rgba(61, 52, 48, 0.01) !important;
            padding: 1rem 0 !important;
        }
        body.cozy-theme-active .navbar .navbar-brand {
            color: var(--accent-coral) !important;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            letter-spacing: -0.02em;
        }
        body.cozy-theme-active .navbar .nav-link {
            color: var(--text-dark-espresso) !important;
            font-weight: 600;
            opacity: 0.85;
            transition: all 0.2s ease;
        }
        body.cozy-theme-active .navbar .nav-link:hover {
            opacity: 1;
            color: var(--accent-coral) !important;
        }

        /* Cozy Solid Buttons */
        body.cozy-theme-active .btn-primary {
            background-color: var(--accent-coral) !important;
            border-color: var(--accent-coral) !important;
            color: #FFFFFF !important;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            border-radius: 50px !important;
            padding: 0.6rem 1.6rem !important;
            box-shadow: 0 4px 15px rgba(231, 111, 81, 0.1) !important;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        body.cozy-theme-active .btn-primary:hover {
            background-color: #d1563f !important;
            border-color: #d1563f !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 20px rgba(231, 111, 81, 0.2) !important;
        }
        body.cozy-theme-active .btn-outline-primary {
            background-color: #FFFFFF !important;
            border: 2px solid var(--accent-sage) !important;
            color: var(--accent-sage) !important;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            border-radius: 50px !important;
            padding: 0.5rem 1.5rem !important;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        body.cozy-theme-active .btn-outline-primary:hover {
            background-color: var(--accent-sage) !important;
            color: #FFFFFF !important;
            border-color: var(--accent-sage) !important;
            transform: translateY(-2px) !important;
        }

        /* Cozy Cards (Stores, Products, Reviews, Vouchers) */
        body.cozy-theme-active .card {
            background-color: #FFFFFF !important;
            border: 1.5px solid #E2E8F0 !important;
            border-radius: 24px !important;
            box-shadow: 0 4px 20px rgba(61, 52, 48, 0.01) !important;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
            overflow: hidden !important;
        }
        body.cozy-theme-active .card:hover {
            border-color: var(--accent-coral) !important;
            transform: translateY(-4px) !important;
            box-shadow: 0 16px 35px rgba(231, 111, 81, 0.05) !important;
        }
        body.cozy-theme-active .card-header {
            background-color: #FAF6F0 !important;
            border-bottom: 1.5px solid #E2E8F0 !important;
            padding: 1.25rem 1.5rem !important;
        }
        body.cozy-theme-active .card-footer {
            background-color: #FAF6F0 !important;
            border-top: 1.5px solid #E2E8F0 !important;
        }

        /* Form Controls & Inputs */
        body.cozy-theme-active .form-control,
        body.cozy-theme-active .form-select {
            background-color: #FFFFFF !important;
            border: 1.5px solid #E2E8F0 !important;
            border-radius: 14px !important;
            color: var(--text-dark-espresso) !important;
            padding: 0.75rem 1.25rem !important;
            transition: all 0.3s ease !important;
        }
        body.cozy-theme-active .form-control:focus,
        body.cozy-theme-active .form-select:focus {
            border-color: var(--accent-coral) !important;
            box-shadow: 0 0 0 4px rgba(231, 111, 81, 0.12) !important;
        }

        /* List Groups */
        body.cozy-theme-active .list-group-item {
            background-color: #FFFFFF !important;
            border: 1.5px solid #E2E8F0 !important;
            color: var(--text-dark-espresso) !important;
            padding: 1rem 1.25rem !important;
            border-radius: 12px !important;
            margin-bottom: 0.5rem !important;
        }
        
        /* Dropdowns */
        body.cozy-theme-active .dropdown-menu {
            background-color: #FFFFFF !important;
            border: 1.5px solid #E2E8F0 !important;
            border-radius: 16px !important;
            box-shadow: 0 10px 30px rgba(61, 52, 48, 0.04) !important;
            padding: 0.5rem !important;
        }
        body.cozy-theme-active .dropdown-item {
            color: var(--text-dark-espresso) !important;
            font-weight: 500 !important;
            padding: 0.6rem 1.25rem !important;
            border-radius: 10px !important;
            transition: all 0.2s ease !important;
        }
        body.cozy-theme-active .dropdown-item:hover {
            background-color: #FFF0EE !important;
            color: var(--accent-coral) !important;
        }

        /* Tables */
        body.cozy-theme-active .table {
            background-color: #FFFFFF !important;
            border: 1.5px solid #E2E8F0 !important;
            color: var(--text-dark-espresso) !important;
            border-radius: 16px !important;
            overflow: hidden !important;
        }
        body.cozy-theme-active .table th {
            background-color: #FAF6F0 !important;
            color: var(--text-dark-espresso) !important;
            border-bottom: 1.5px solid #E2E8F0 !important;
            font-weight: 700 !important;
        }

        /* Badges */
        body.cozy-theme-active .badge.bg-primary {
            background-color: var(--accent-coral) !important;
            color: #FFFFFF !important;
        }
        body.cozy-theme-active .badge.bg-secondary {
            background-color: var(--accent-sage) !important;
            color: #FFFFFF !important;
        }
        body.cozy-theme-active .badge.bg-success {
            background-color: #8A9A86 !important;
            color: #FFFFFF !important;
        }

        /* Footers */
        body.cozy-theme-active footer {
            background-color: #FAF6F0 !important;
            border-top: 1px solid rgba(61, 52, 48, 0.06) !important;
            color: var(--text-dark-espresso) !important;
        }
    </style>
@endif
</head>
<body class="antialiased {{ !(request()->is('admin*') || request()->is('partner*') || request()->is('manager*')) ? 'cozy-theme-active' : '' }}">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="{{ app()->has('current_city') ? route('city.home', ['city_slug' => app('current_city')->slug]) : url('/') }}">Gift-XP</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ app()->has('current_city') ? route('city.home', ['city_slug' => app('current_city')->slug]) : url('/') }}">Home</a>
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
                            @php $partnerUser = Auth::guard('partner')->user(); @endphp
                            <div class="dropdown">
                                <a class="btn btn-outline-primary rounded-pill dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    {{ $partnerUser->role === 'owner' ? 'Partner' : 'Manager' }}: {{ $partnerUser->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                    @if($partnerUser->isOwner())
                                        <li><a class="dropdown-item" href="{{ route('partner.dashboard') }}">Dashboard</a></li>
                                        <li><a class="dropdown-item" href="{{ route('partner.managers.index') }}">Branch Managers</a></li>
                                    @else
                                        <li><a class="dropdown-item" href="{{ route('manager.vouchers.scan') }}">QR Scanner</a></li>
                                        <li><a class="dropdown-item" href="{{ route('manager.vouchers.transactions') }}">Transactions</a></li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('partner.logout') }}" method="POST">
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

    @auth('web')
        @php
            $unreviewedCount = \App\Models\Voucher::whereHas('order', function($q) {
                $q->where('gifter_id', Auth::id());
            })
            ->whereNotNull('claimed_at')
            ->whereDoesntHave('review')
            ->count();
        @endphp

        @if($unreviewedCount > 0 && request()->routeIs(['city.home', 'store.show', 'product.show']))
            <div class="review-notification-bar bg-primary text-white py-2 shadow-sm animate-pulse" style="background: linear-gradient(90deg, var(--bs-primary), #6f42c1); position: sticky; top: 60px; z-index: 1010; overflow: hidden; border-top: 2px solid #ffc107; border-bottom: 2px solid #ffc107; box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);">
                <div class="container d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-stars fs-4 me-2 text-warning"></i>
                        <span class="fw-bold">
                            {{ $unreviewedCount }} {{ Str::plural('gift', $unreviewedCount) }} {{ $unreviewedCount > 1 ? 'have' : 'has' }} been claimed! 
                            <span class="d-none d-md-inline">Share your thoughts with a review.</span>
                        </span>
                    </div>
                    <a href="{{ route('my-gifts', ['status' => 'needs_review']) }}" class="btn btn-sm btn-warning rounded-pill px-4 fw-bold shadow-sm">
                        Review Now
                    </a>
                </div>
                <div class="glow-effect"></div>
            </div>

            <style>
                .review-notification-bar {
                    animation: border-pulse 2s infinite ease-in-out;
                }
                @keyframes border-pulse {
                    0% { border-top-color: #ffc107; border-bottom-color: #ffc107; box-shadow: 0 4px 5px rgba(255, 193, 7, 0.3); }
                    50% { border-top-color: #fff; border-bottom-color: #fff; box-shadow: 0 4px 20px rgba(255, 193, 7, 0.6); }
                    100% { border-top-color: #ffc107; border-bottom-color: #ffc107; box-shadow: 0 4px 5px rgba(255, 193, 7, 0.3); }
                }
                .glow-effect {
                    position: absolute;
                    top: 0;
                    left: -100%;
                    width: 50%;
                    height: 100%;
                    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
                    animation: slide-glow 4s infinite linear;
                }
                @keyframes slide-glow {
                    to { left: 200%; }
                }
            </style>
        @endif
    @endauth

    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('status'))
                <div class="alert alert-info alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
        @yield('content')
    </main>

    <!-- Mobile Bottom Navigation Bar -->
    <nav class="mobile-bottom-nav border-top">
        @if(Auth::guard('partner')->check())
            @php $partnerUser = Auth::guard('partner')->user(); @endphp
            @if($partnerUser->isOwner())
                <div class="nav-item">
                    <a href="{{ route('partner.dashboard') }}" class="nav-link {{ Request::routeIs('partner.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('partner.vouchers.index') }}" class="nav-link {{ Request::routeIs('partner.vouchers.index') ? 'active' : '' }}">
                        <i class="bi bi-gift"></i>
                        <span>Vouchers</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('partner.managers.index') }}" class="nav-link {{ Request::routeIs('partner.managers.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>Managers</span>
                    </a>
                </div>
            @else
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
                    <form action="{{ route('partner.logout') }}" method="POST" id="partnerLogoutForm" class="d-none">@csrf</form>
                    <a href="javascript:void(0)" onclick="document.getElementById('partnerLogoutForm').submit()" class="nav-link text-danger">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </a>
                </div>
            @endif
        @else
            <div class="nav-item">
                <a href="{{ app()->has('current_city') ? route('city.home', ['city_slug' => app('current_city')->slug]) : url('/') }}" class="nav-link {{ Request::is('/') || Request::routeIs('city.home') ? 'active' : '' }}">
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
            <h4 class="fw-bold text-primary mb-3">
                <a href="{{ app()->has('current_city') ? route('city.home', ['city_slug' => app('current_city')->slug]) : url('/') }}" class="text-decoration-none text-primary">Gift-XP</a>
            </h4>
            <p class="text-muted mb-4">Sharing joy, one gift at a time.</p>
            <div class="mb-4">
                <a href="{{ route('page.about') }}" class="text-muted text-decoration-none mx-2 small">About Us</a>
                <a href="{{ route('page.terms') }}" class="text-muted text-decoration-none mx-2 small">Terms of Service</a>
                <a href="{{ route('page.privacy') }}" class="text-muted text-decoration-none mx-2 small">Privacy Policy</a>
            </div>
            <p class="text-muted small mb-0">&copy; {{ date('Y') }} Gift-XP. All rights reserved.</p>
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
