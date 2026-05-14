@extends('layouts.app')

@section('title', 'Branch Manager Login')

@section('content')
<style>
    /* Manager specific theme override */
    body {
        background-color: #f0fdf4; /* Emerald 50 */
    }
    .manager-card {
        background-color: #ffffff;
        border: none;
        border-radius: 1.5rem;
        box-shadow: 0 20px 25px -5px rgba(16, 185, 129, 0.1), 0 10px 10px -5px rgba(16, 185, 129, 0.04);
        position: relative;
        overflow: hidden;
    }
    .manager-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 6px;
        background: linear-gradient(90deg, #10b981, #34d399); /* Emerald gradient */
    }
    .manager-icon-bg {
        background-color: #d1fae5; /* Emerald 100 */
        color: #059669; /* Emerald 600 */
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem auto;
    }
    .manager-input {
        background-color: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
    }
    .manager-input:focus {
        background-color: #ffffff;
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    }
    .manager-btn {
        background-color: #10b981;
        border: none;
        color: white;
    }
    .manager-btn:hover {
        background-color: #059669;
        color: white;
    }
    .navbar {
        display: none !important;
    }
</style>

<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="row w-100 justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card manager-card p-4 p-md-5">
                <div class="card-body px-0 px-md-2">
                    <div class="text-center mb-4">
                        <div class="manager-icon-bg">
                            <i class="bi bi-qr-code-scan display-5"></i>
                        </div>
                        <h1 class="h3 fw-bold text-dark mb-1">Store Terminal</h1>
                        <p class="text-muted small">Branch Manager Access</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger rounded-3 px-4 mb-4 small border-0 bg-danger bg-opacity-10 text-danger">
                            <i class="bi bi-exclamation-circle me-2"></i> {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('manager.login.submit') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary">Manager Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                                <input type="email" name="email" class="form-control manager-input border-start-0 ps-0" value="{{ old('email') }}" placeholder="manager@branch.com" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control manager-input border-start-0 ps-0" placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label small text-muted user-select-none" for="remember">
                                    Keep me signed in
                                </label>
                            </div>
                        </div>

                        <div class="d-grid mt-2">
                            <button type="submit" class="btn manager-btn py-3 fw-bold rounded-pill shadow-sm">
                                Open Scanner Terminal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ url('/') }}" class="text-muted text-decoration-none small opacity-75 hover-opacity-100">
                    <i class="bi bi-arrow-left me-1"></i> Exit to Main Site
                </a>
            </div>
        </div>
    </div>
</div>
@endsection