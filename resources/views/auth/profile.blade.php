@extends('layouts.app')

@section('title', 'My Profile - Beam Gifts')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            @include('partials.account-menu')
        </div>
        <div class="col-md-9">
            <h1 class="h3 fw-bold mb-4 text-primary">Account Settings</h1>
            
            <div class="card shadow-sm border-0 p-4 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">Personal Information</h5>
                    <div class="row mb-3">
                        <div class="col-sm-3 text-muted">Full Name</div>
                        <div class="col-sm-9 fw-bold">{{ $gifter->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 text-muted">Email Address</div>
                        <div class="col-sm-9 fw-bold">{{ $gifter->email }}</div>
                    </div>
                    <div class="mt-4">
                        <button class="btn btn-outline-primary btn-sm rounded-pill px-4">Edit Profile</button>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 p-4 border-start border-4 border-primary">
                <div class="card-body">
                    <h5 class="fw-bold mb-2">Recent Activity</h5>
                    <p class="text-muted small">You haven't bought any gifts yet. Start exploring cities to find the perfect gift!</p>
                    <a href="{{ url('/') }}" class="btn btn-primary rounded-pill btn-sm px-4">Explore Gifts</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
