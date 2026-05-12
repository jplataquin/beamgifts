@extends('layouts.app')

@section('title', 'Payment Successful - Beam Gifts')

@section('content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 p-5 rounded-4">
                <div class="card-body">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill display-1 text-success"></i>
                    </div>
                    <h1 class="h3 fw-bold mb-3">Thank you for your gift!</h1>
                    <p class="text-muted mb-5 lead">Your payment has been processed successfully. We are now generating your digital vouchers. You can find them in your account shortly.</p>
                    
                    <div class="d-grid gap-3">
                        <a href="{{ route('my-gifts') }}" class="btn btn-primary rounded-pill py-3 fw-bold">View My Gifts</a>
                        <a href="{{ url('/') }}" class="btn btn-light rounded-pill py-3">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
