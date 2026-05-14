@extends('layouts.app')

@section('title', 'Join Beam Gifts')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4 shadow-sm border-0">
                <div class="card-body">
                    <h2 class="h4 fw-bold text-primary mb-4 text-center">Start Gifting</h2>
                    <p class="text-center text-muted small mb-4">Create your account to start sending digital gifts</p>
                    
                    <form method="POST" action="{{ route('register.submit') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}" required autofocus>
                                @error('first_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <div class="mb-4">
                            <div class="form-check small">
                                <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" name="terms" id="terms" required>
                                <label class="form-check-label text-muted" for="terms">
                                    I agree to the <a href="{{ route('page.terms') }}" target="_blank" class="text-primary text-decoration-none fw-bold">Terms of Service</a> and <a href="{{ route('page.privacy') }}" target="_blank" class="text-primary text-decoration-none fw-bold">Privacy Policy</a>
                                </label>
                                @error('terms')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold">Sign Up</button>
                    </form>
                </div>
            </div>
            <div class="text-center mt-4">
                <p class="text-muted small">Already have an account? <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Login here</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
