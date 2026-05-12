@extends('layouts.app')

@section('title', 'Manager Login')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0 rounded-4 p-4 p-md-5">
                <div class="card-body">
                    <div class="text-center mb-5">
                        <h1 class="h3 fw-bold text-primary mb-2">Manager Access</h1>
                        <p class="text-muted">Redeem vouchers and view branch history.</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger rounded-pill px-4 mb-4 small border-0">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('manager.login.submit') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">Email Address</label>
                            <input type="email" name="email" class="form-control rounded-pill py-2 shadow-none" value="{{ old('email') }}" placeholder="manager@example.com" required autofocus>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">Password</label>
                            <input type="password" name="password" class="form-control rounded-pill py-2 shadow-none" placeholder="••••••••" required>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label small text-muted" for="remember">
                                    Remember me on this device
                                </label>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary rounded-pill py-3 fw-bold shadow-sm">
                                Login to Branch
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ url('/') }}" class="text-muted text-decoration-none small">&larr; Return to Home</a>
            </div>
        </div>
    </div>
</div>
@endsection