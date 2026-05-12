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
                    <div class="card p-4">
                        <h3 class="h5 mb-0 text-dark">{{ $city->name }}</h3>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
