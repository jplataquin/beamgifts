@extends('layouts.app')

@section('title', 'Become a Partner - Beam Gifts')

@section('content')
<div class="container py-5">
    <div class="row align-items-center g-5">
        <div class="col-lg-6">
            <h1 class="display-4 fw-bold text-primary mb-4">Grow your business with Beam Gifts</h1>
            <p class="lead text-muted mb-4">
                Join our network of local partners and start selling digital vouchers to a wider audience. Our platform makes it easy to manage your products, track sales, and provide a seamless gifting experience.
            </p>
            
            <div class="mb-5">
                <div class="d-flex mb-4">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; flex-shrink: 0;">
                        <i class="bi bi-graph-up fs-4"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold h5 mb-1">Increase Sales</h4>
                        <p class="text-muted small mb-0">Reach new customers who are looking for unique digital gifts from local businesses like yours.</p>
                    </div>
                </div>
                
                <div class="d-flex mb-4">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; flex-shrink: 0;">
                        <i class="bi bi-qr-code fs-4"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold h5 mb-1">Simple Redemption</h4>
                        <p class="text-muted small mb-0">Use our built-in QR scanner to instantly validate and claim vouchers with zero hassle.</p>
                    </div>
                </div>

                <div class="d-flex">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; flex-shrink: 0;">
                        <i class="bi bi-shield-check fs-4"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold h5 mb-1">Secure & Trusted</h4>
                        <p class="text-muted small mb-0">Benefit from our secure payment integration and a platform built with trust at its core.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card shadow border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white py-4 border-0">
                    <h3 class="h4 fw-bold mb-0 text-center">Partner Application</h3>
                </div>
                <div class="card-body p-4 p-md-5">
                    @if(session('success'))
                        <div class="alert alert-success rounded-pill px-4 mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger rounded-4 mb-4 small">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('page.partner-apply') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="business_name" class="form-label small fw-bold">Business Name</label>
                            <input type="text" class="form-control bg-light border-0" id="business_name" name="business_name" value="{{ old('business_name') }}" placeholder="e.g. Sunny Day Cafe" required>
                        </div>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="contact_person" class="form-label small fw-bold">Contact Person</label>
                                <input type="text" class="form-control bg-light border-0" id="contact_person" name="contact_person" value="{{ old('contact_person') }}" placeholder="Your Name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="city" class="form-label small fw-bold">City</label>
                                <input type="text" class="form-control bg-light border-0" id="city" name="city" value="{{ old('city') }}" placeholder="Your City" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label small fw-bold">Email Address</label>
                            <input type="email" class="form-control bg-light border-0" id="email" name="email" value="{{ old('email') }}" placeholder="business@example.com" required>
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="form-label small fw-bold">Phone Number</label>
                            <input type="text" class="form-control bg-light border-0" id="phone" name="phone" value="{{ old('phone') }}" placeholder="e.g. +63 912 345 6789" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm">Submit Application</button>
                        </div>
                        
                        <p class="text-center text-muted small mt-4 mb-0">
                            By submitting, you agree to our <a href="{{ route('page.terms') }}" class="text-primary text-decoration-none">Terms of Service</a>.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
