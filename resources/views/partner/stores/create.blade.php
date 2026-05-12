@extends('layouts.app')

@section('title', 'Add New Store')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('partner.stores.index') }}" class="btn btn-light rounded-pill me-3">
                    &larr; Back
                </a>
                <h1 class="h3 fw-bold mb-0 text-primary">Add New Store</h1>
            </div>

            <div class="card shadow-sm border-0 p-4">
                <div class="card-body">
                    <form action="{{ route('partner.stores.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold">Store Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. My Awesome Gift Shop" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">Store Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Tell customers about your store...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="logo" class="form-label fw-bold">Store Logo (Optional)</label>
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/*">
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text small text-muted">Chunked upload will be supported in the next phase for larger files.</div>
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold">Create Store</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
