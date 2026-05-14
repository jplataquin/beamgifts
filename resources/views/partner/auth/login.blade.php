@extends('layouts.app')

@section('title', 'Partner Login')

@section('content')
<style>
    /* Partner specific split-screen override */
    body, html {
        height: 100%;
        background-color: #f8f9fa;
    }
    .navbar {
        display: none !important; /* Hide standard nav for this dedicated page */
    }
    .split-layout {
        display: flex;
        min-height: 100vh;
        width: 100vw;
        margin: 0;
        padding: 0;
    }
    .split-left {
        flex: 1;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: white;
        padding: 3rem;
        position: relative;
        overflow: hidden;
    }
    .split-left::before {
        content: "";
        position: absolute;
        top: -10%;
        left: -10%;
        width: 120%;
        height: 120%;
        background-image: radial-gradient(circle, rgba(255,255,255,0.1) 10%, transparent 10%), radial-gradient(circle, rgba(255,255,255,0.1) 10%, transparent 10%);
        background-size: 50px 50px;
        background-position: 0 0, 25px 25px;
        z-index: 1;
        opacity: 0.5;
    }
    .left-content {
        position: relative;
        z-index: 2;
        text-align: center;
        max-width: 400px;
    }
    .split-right {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: white;
        padding: 3rem;
    }
    .form-container {
        width: 100%;
        max-width: 420px;
    }
    .partner-input {
        background-color: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
    }
    .partner-input:focus {
        background-color: white;
        border-color: #8b5cf6;
        box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
    }
    @media (max-width: 768px) {
        .split-left {
            display: none;
        }
    }
</style>

<div class="split-layout">
    <div class="split-left">
        <div class="left-content">
            <i class="bi bi-shop-window display-1 mb-4"></i>
            <h1 class="fw-bold mb-3">Partner Portal</h1>
            <p class="lead opacity-75">Grow your business, manage your digital storefront, and delight new customers through Beam Gifts.</p>
        </div>
    </div>
    
    <div class="split-right">
        <div class="form-container">
            <div class="mb-5 text-center d-md-none">
                <i class="bi bi-shop-window text-primary display-4 mb-2"></i>
                <h2 class="fw-bold text-dark">Partner Portal</h2>
            </div>
            
            <h3 class="fw-bold text-dark mb-1">Welcome back</h3>
            <p class="text-muted mb-4">Sign in to your merchant dashboard.</p>
            
            <form method="POST" action="{{ route('partner.login.submit') }}">
                @csrf
                
                <div class="mb-4">
                    <label for="email" class="form-label fw-bold small text-dark">Business Email</label>
                    <input type="email" class="form-control partner-input @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@yourbusiness.com">
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label fw-bold small text-dark">Password</label>
                    <input type="password" class="form-control partner-input @error('password') is-invalid @enderror" id="password" name="password" required placeholder="••••••••">
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-dark w-100 py-3 rounded-pill fw-bold mb-4" style="background-color: #8b5cf6; border-color: #8b5cf6;">Sign In to Dashboard</button>
            </form>

            <div class="text-center">
                <p class="text-muted small mb-2">Want to become a partner?</p>
                <a href="{{ url('/') }}" class="text-decoration-none fw-bold" style="color: #8b5cf6;">Contact our sales team</a>
                <div class="mt-4 pt-4 border-top">
                    <a href="{{ url('/') }}" class="text-muted text-decoration-none small"><i class="bi bi-arrow-left me-1"></i> Back to main site</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection