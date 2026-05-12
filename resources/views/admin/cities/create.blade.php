@extends('layouts.app')

@section('title', 'Add New City')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('admin.cities.index') }}" class="btn btn-light rounded-pill me-3">
                    &larr; Back
                </a>
                <h1 class="h3 fw-bold mb-0 text-primary">Add New City</h1>
            </div>

            <div class="card shadow-sm border-0 p-4">
                <div class="card-body">
                    <form action="{{ route('admin.cities.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="name" class="form-label">City Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Quezon City" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">The slug will be automatically generated.</div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                <label class="form-check-label" for="is_active">Make this city active immediately</label>
                            </div>
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold">Add City</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
