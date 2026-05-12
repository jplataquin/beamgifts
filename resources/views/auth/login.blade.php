@extends('layouts.app')

@section('title', 'Login - Beam Gifts')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4 shadow-sm border-0">
                <div class="card-body">
                    <h2 class="h4 fw-bold text-primary mb-4 text-center">Welcome Back</h2>
                    <p class="text-center text-muted small mb-4">Login to send gifts and track your orders</p>
                    
                    <form method="POST" action="{{ route('login.submit') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold">Login</button>
                    </form>
                </div>
            </div>
            <div class="text-center mt-4">
                <p class="text-muted small">Don't have an account? <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Create one</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
