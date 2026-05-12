@extends('layouts.app')

@section('title', 'My Profile - Beam Gifts')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 h-100">
                <div class="card-body p-0">
                    <h5 class="fw-bold text-primary mb-4">Account Menu</h5>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('profile') }}" class="list-group-item list-group-item-action active rounded-pill mb-1">My Profile</a>
                        <a href="{{ route('my-gifts') }}" class="list-group-item list-group-item-action rounded-pill mb-1 border-0">My Gifts</a>
                        <a href="#" class="list-group-item list-group-item-action rounded-pill mb-1 border-0 text-muted">Order History</a>
                        <form action="{{ route('logout') }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100 rounded-pill">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
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
