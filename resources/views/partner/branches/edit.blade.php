@extends('layouts.app')

@section('title', 'Edit Branch')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('partner.branches.index') }}" class="btn btn-light rounded-pill me-3">
                    &larr; Back
                </a>
                <h1 class="h3 fw-bold mb-0 text-primary">Edit Branch: {{ $branch->name }}</h1>
            </div>

            <div class="card shadow-sm border-0 p-4">
                <div class="card-body">
                    <form action="{{ route('partner.branches.update', $branch) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="city_id" class="form-label fw-bold">Select City</label>
                            <select class="form-select @error('city_id') is-invalid @enderror" id="city_id" name="city_id" required>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ old('city_id', $branch->city_id) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                @endforeach
                            </select>
                            @error('city_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold">Branch Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $branch->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="address" class="form-label fw-bold">Complete Address</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $branch->address) }}" required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="form-label fw-bold">Contact Number (Optional)</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $branch->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="map_url" class="form-label fw-bold">Google Maps URL (Optional)</label>
                            <input type="url" class="form-control @error('map_url') is-invalid @enderror" id="map_url" name="map_url" value="{{ old('map_url', $branch->map_url) }}" placeholder="https://goo.gl/maps/...">
                            <div class="form-text">Paste the link from Google Maps to help recipients find this branch.</div>
                            @error('map_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold">Update Branch</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
