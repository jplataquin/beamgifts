@extends('layouts.app')

@section('title', 'Add New Category')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-light rounded-pill me-3">
                    &larr; Back
                </a>
                <h1 class="h3 fw-bold mb-0 text-primary">Add New Category</h1>
            </div>

            <div class="card shadow-sm border-0 p-4">
                <div class="card-body">
                    <form action="{{ route('admin.categories.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold">Category Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Experiences" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">Description (Optional)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Briefly describe what this category is for...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold">Create Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
