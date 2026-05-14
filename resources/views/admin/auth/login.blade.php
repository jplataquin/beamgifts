@extends('layouts.app')

@section('title', 'Admin Portal Login')

@section('content')
<style>
    /* Admin specific dark theme override */
    body {
        background-color: #0f172a !important; /* Slate 900 */
        background-image: radial-gradient(circle at top right, #1e293b, transparent 40%),
                          radial-gradient(circle at bottom left, #1e293b, transparent 40%);
    }
    .admin-card {
        background-color: #1e293b; /* Slate 800 */
        border: 1px solid #334155; /* Slate 700 */
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    .admin-input {
        background-color: #0f172a !important;
        border: 1px solid #334155 !important;
        color: #f8fafc !important;
    }
    .admin-input:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25) !important;
    }
    .admin-label {
        color: #94a3b8; /* Slate 400 */
    }
    .admin-btn {
        background: linear-gradient(to right, #3b82f6, #2563eb);
        border: none;
    }
    .admin-btn:hover {
        background: linear-gradient(to right, #2563eb, #1d4ed8);
    }
    .admin-icon {
        color: #38bdf8; /* Sky 400 */
    }
    /* Hide the default navbar for a fully immersive login */
    .navbar {
        display: none !important;
    }
</style>

<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="row w-100 justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="text-center mb-4">
                <i class="bi bi-shield-lock-fill admin-icon mb-3 d-block" style="font-size: 3rem;"></i>
                <h1 class="h3 fw-bold text-white mb-0">System Control</h1>
                <p class="admin-label small">Restricted Admin Access Only</p>
            </div>

            <div class="card admin-card rounded-4 p-4">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.login.submit') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="email" class="form-label admin-label small fw-bold text-uppercase">Admin ID</label>
                            <input type="email" class="form-control admin-input rounded-3 py-2 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@system.com">
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label admin-label small fw-bold text-uppercase">Security Key</label>
                            <input type="password" class="form-control admin-input rounded-3 py-2 @error('password') is-invalid @enderror" id="password" name="password" required placeholder="••••••••">
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary admin-btn w-100 py-3 fw-bold mt-2 rounded-3 text-uppercase tracking-wide">Authenticate</button>
                    </form>
                </div>
            </div>
            
            <div class="text-center mt-5">
                <a href="{{ url('/') }}" class="admin-label text-decoration-none small"><i class="bi bi-arrow-left me-1"></i> Return to Public Site</a>
            </div>
        </div>
    </div>
</div>
@endsection