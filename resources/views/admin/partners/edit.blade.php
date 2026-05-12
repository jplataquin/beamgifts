@extends('layouts.app')

@section('title', 'Edit Partner')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('admin.partners.index') }}" class="btn btn-light rounded-pill me-3">
                    &larr; Back
                </a>
                <h1 class="h3 fw-bold mb-0 text-primary">Edit Partner: {{ $partner->name }}</h1>
            </div>

            <div class="card shadow-sm border-0 p-4">
                <div class="card-body">
                    <form action="{{ route('admin.partners.update', $partner) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Contact Person Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $partner->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $partner->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="phone1" class="form-label">Mobile Number 1</label>
                                <input type="text" class="form-control @error('phone1') is-invalid @enderror" id="phone1" name="phone1" value="{{ old('phone1', $partner->phone1) }}">
                                @error('phone1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone2" class="form-label">Mobile Number 2</label>
                                <input type="text" class="form-control @error('phone2') is-invalid @enderror" id="phone2" name="phone2" value="{{ old('phone2', $partner->phone2) }}">
                                @error('phone2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="business_name" class="form-label">Business Name</label>
                            <input type="text" class="form-control @error('business_name') is-invalid @enderror" id="business_name" name="business_name" value="{{ old('business_name', $partner->business_name) }}" required>
                            @error('business_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="card bg-light border-0 p-3 mb-4">
                            <h6 class="fw-bold mb-2 small text-muted text-uppercase">Security</h6>
                            <p class="small text-muted mb-3">Leave password fields blank if you don't want to change the password.</p>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                </div>
                            </div>
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold">Update Partner Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
