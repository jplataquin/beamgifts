@extends('layouts.app')

@section('title', 'Welcome to Beam Gifts')

@section('content')
<div class="container py-5 text-center">
    <h1 class="display-4 fw-bold text-primary mb-4">Find the perfect gift</h1>
    <p class="lead mb-5">Select your city to start exploring digital vouchers from local stores.</p>
    
    <div class="row justify-content-center">
        @foreach(\App\Models\City::where('is_active', true)->get() as $city)
            <div class="col-md-3 mb-4">
                <a href="{{ route('city.home', ['city_slug' => $city->slug]) }}" class="text-decoration-none">
                    <div class="card p-4 h-100 shadow-sm border-0">
                        <h3 class="h5 mb-0 text-dark">{{ $city->name }}</h3>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>

<div class="container py-5 mt-4 border-top">
    <div class="row g-4 text-center justify-content-center">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 p-4">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3">
                        <i class="bi bi-shop display-4 text-primary"></i>
                    </div>
                    <h3 class="h5 fw-bold text-dark">Become a Partner</h3>
                    <p class="text-muted small mb-4 flex-grow-1">Join our platform and start selling digital vouchers for your local business.</p>
                    <div>
                        <a href="{{ route('page.partner-intro') }}" class="btn btn-outline-primary rounded-pill px-4">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 p-4">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3">
                        <i class="bi bi-question-circle display-4 text-primary"></i>
                    </div>
                    <h3 class="h5 fw-bold text-dark">FAQ</h3>
                    <p class="text-muted small mb-4 flex-grow-1">Have questions? Browse our frequently asked questions to find the answers you need.</p>
                    <div>
                        <a href="#" class="btn btn-outline-primary rounded-pill px-4">View FAQ</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 p-4">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3">
                        <i class="bi bi-download display-4 text-primary"></i>
                    </div>
                    <h3 class="h5 fw-bold text-dark">Install</h3>
                    <p class="text-muted small mb-4 flex-grow-1">Get the Beam Gifts app on your device for a faster and smoother gifting experience.</p>
                    <div>
                        <a href="#" class="btn btn-outline-primary rounded-pill px-4">Install App</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
