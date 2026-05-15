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
            
            @if(session('success'))
                <div class="alert alert-success rounded-pill px-4 mb-4">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger rounded-4 mb-4">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="card shadow-sm border-0 p-4 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">Personal Information</h5>
                    <div class="row mb-3">
                        <div class="col-sm-3 text-muted">First Name</div>
                        <div class="col-sm-9 fw-bold">{{ $gifter->first_name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 text-muted">Last Name</div>
                        <div class="col-sm-9 fw-bold">{{ $gifter->last_name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 text-muted">Email Address</div>
                        <div class="col-sm-9 fw-bold">{{ $gifter->email }}</div>
                    </div>
                    <div class="mt-4">
                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            Edit Profile
                        </button>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 p-4 border-start border-4 border-primary">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Recent Activity</h5>
                    @if($recentOrders->isEmpty())
                        <p class="text-muted small">You haven't bought any gifts yet. Start exploring cities to find the perfect gift!</p>
                        <a href="{{ url('/') }}" class="btn btn-primary rounded-pill btn-sm px-4 mt-2">Explore Gifts</a>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($recentOrders as $order)
                                <a href="{{ route('my-orders.show', $order) }}" class="list-group-item list-group-item-action px-0 py-3 border-bottom border-light">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1 fw-bold">Order #{{ $order->id }}</h6>
                                            <small class="text-muted">{{ $order->items->sum('quantity') }} items &bull; ₱{{ number_format($order->total_amount, 2) }}</small>
                                        </div>
                                        <div class="text-end">
                                            <small class="d-block text-muted mb-1">{{ $order->created_at->format('M d, Y') }}</small>
                                            @if($order->status === 'paid')
                                                <span class="badge bg-success rounded-pill" style="font-size: 0.7em;">Paid</span>
                                            @else
                                                <span class="badge bg-secondary rounded-pill" style="font-size: 0.7em;">{{ ucfirst($order->status) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('my-orders') }}" class="btn btn-link btn-sm text-decoration-none px-0">View all orders &rarr;</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-0">
                <h5 class="modal-title fw-bold text-primary" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label small fw-bold">First Name</label>
                            <input type="text" class="form-control bg-light border-0" id="first_name" name="first_name" value="{{ old('first_name', $gifter->first_name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label small fw-bold">Last Name</label>
                            <input type="text" class="form-control bg-light border-0" id="last_name" name="last_name" value="{{ old('last_name', $gifter->last_name) }}" required>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="email" class="form-label small fw-bold">Email Address</label>
                        <input type="email" class="form-control bg-light border-0" id="email" name="email" value="{{ old('email', $gifter->email) }}" required>
                    </div>

                    <hr class="my-4">
                    
                    <h6 class="fw-bold mb-3">Change Password <span class="text-muted fw-normal small">(Leave blank to keep current)</span></h6>
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label small fw-bold">Current Password</label>
                        <input type="password" class="form-control bg-light border-0" id="current_password" name="current_password">
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label small fw-bold">New Password</label>
                        <input type="password" class="form-control bg-light border-0" id="password" name="password">
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label small fw-bold">Confirm New Password</label>
                        <input type="password" class="form-control bg-light border-0" id="password_confirmation" name="password_confirmation">
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
