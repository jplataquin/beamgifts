@extends('layouts.app')

@section('title', 'Edit Store Settings')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('partner.store.show') }}" class="btn btn-light rounded-pill me-3">
                    &larr; Back
                </a>
                <h1 class="h3 fw-bold mb-0 text-primary">Edit Store Settings</h1>
            </div>

            <div class="card shadow-sm border-0 p-4">
                <div class="card-body">
                    <form action="{{ route('partner.store.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-4">
                            <label for="name" class="form-label">Store Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $store->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $store->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="logo" class="form-label">Store Logo</label>
                            @if($store->logo)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $store->logo) }}" alt="Current Logo" class="img-thumbnail" style="height: 100px;">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/*">
                            <div class="form-text">Recommended: Square image, max 2MB.</div>
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
