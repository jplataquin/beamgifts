@extends('layouts.app')

@section('title', 'Welcome to Gift-XP')

@section('content')
<div class="container py-5 text-center">
    <h1 class="display-4 fw-bold text-primary mb-4">Find the perfect gift</h1>
    <p class="lead mb-5">Select your city to start exploring digital vouchers from local stores.</p>
    
    <div class="row justify-content-center g-4">
        @foreach(\App\Models\City::where('is_active', true)->get() as $city)
            <div class="col-md-4 col-lg-3">
                <a href="{{ route('city.home', ['city_slug' => $city->slug]) }}" class="text-decoration-none">
                    <div class="card h-100 shadow-sm border-0 transition-hover city-tile overflow-hidden">
                        <div class="card-body p-4 d-flex align-items-center">
                            <div class="city-icon bg-light text-primary rounded-circle me-3 shadow-sm">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="h5 fw-bold mb-0 text-dark">{{ $city->name }}</h3>
                                <small class="text-muted">Explore gifts</small>
                            </div>
                            <div class="ms-2 text-primary opacity-50">
                                <i class="bi bi-chevron-right fs-5"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

<style>
    .city-tile {
        border-radius: 20px !important;
        border: 1px solid rgba(0,0,0,0.05) !important;
    }
    .city-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        transition: all 0.3s ease;
    }
    .city-tile:hover .city-icon {
        background-color: var(--bs-primary) !important;
        color: white !important;
        transform: scale(1.1);
    }
</style>
</div>

<div class="container py-5 mt-4 border-top">
    <div class="row g-4 text-center justify-content-center">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 p-4 position-relative">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3">
                        <i class="bi bi-shop display-4 text-primary"></i>
                    </div>
                    <h3 class="h5 fw-bold text-dark">Become a Partner</h3>
                    <p class="text-muted small mb-4 flex-grow-1">Join our platform and start selling digital vouchers for your local business.</p>
                    <div>
                        <a href="{{ route('page.partner-intro') }}" class="btn btn-outline-primary rounded-pill px-4 stretched-link">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 p-4 position-relative">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3">
                        <i class="bi bi-question-circle display-4 text-primary"></i>
                    </div>
                    <h3 class="h5 fw-bold text-dark">FAQ</h3>
                    <p class="text-muted small mb-4 flex-grow-1">Have questions? Browse our frequently asked questions to find the answers you need.</p>
                    <div>
                        <a href="#" class="btn btn-outline-primary rounded-pill px-4 stretched-link">View FAQ</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 p-4 position-relative">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3">
                        <i class="bi bi-download display-4 text-primary"></i>
                    </div>
                    <h3 class="h5 fw-bold text-dark">Install</h3>
                    <p class="text-muted small mb-4 flex-grow-1">Get the Gift-XP app on your device for a faster and smoother gifting experience.</p>
                    <div>
                        <a href="#" class="btn btn-outline-primary rounded-pill px-4 stretched-link">Install App</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
