@extends('layouts.app')

@section('title', 'Add New Partner')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('admin.partners.index') }}" class="btn btn-light rounded-pill me-3">
                    &larr; Back
                </a>
                <h1 class="h3 fw-bold mb-0 text-primary">Add New Partner</h1>
            </div>

            <div class="card shadow-sm border-0 p-4">
                <div class="card-body">
                    <form action="{{ route('admin.partners.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Contact Person Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="phone1" class="form-label">Mobile Number 1 (Required)</label>
                                <input type="text" class="form-control @error('phone1') is-invalid @enderror" id="phone1" name="phone1" value="{{ old('phone1') }}">
                                @error('phone1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone2" class="form-label">Mobile Number 2 (Optional)</label>
                                <input type="text" class="form-control @error('phone2') is-invalid @enderror" id="phone2" name="phone2" value="{{ old('phone2') }}">
                                @error('phone2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="business_name" class="form-label">Store / Business Name</label>
                            <input type="text" class="form-control @error('business_name') is-invalid @enderror" id="business_name" name="business_name" value="{{ old('business_name') }}" required>
                            @error('business_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="store_description" class="form-label">Store Description</label>
                            <textarea class="form-control @error('store_description') is-invalid @enderror" id="store_description" name="store_description" rows="3" placeholder="Briefly describe the store...">{{ old('store_description') }}</textarea>
                            @error('store_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold">Create Partner Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
