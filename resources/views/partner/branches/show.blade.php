@extends('layouts.app')

@section('title', 'Branch Details')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('partner.branches.index') }}" class="btn btn-light rounded-pill me-3">
                    &larr; Back
                </a>
                <h1 class="h3 fw-bold mb-0 text-primary">Branch Details</h1>
            </div>

            <div class="card shadow-sm border-0 p-4">
                <div class="card-body">
                    <div class="mb-4">
                        <label class="text-muted small fw-bold text-uppercase mb-1">Branch Name</label>
                        <p class="h4 fw-bold">{{ $branch->name }}</p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="text-muted small fw-bold text-uppercase mb-1">City</label>
                            <p><span class="badge bg-secondary rounded-pill">{{ $branch->city->name }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small fw-bold text-uppercase mb-1">Phone</label>
                            <p>{{ $branch->phone ?: 'Not provided' }}</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted small fw-bold text-uppercase mb-1">Complete Address</label>
                        <p>{{ $branch->address }}</p>
                    </div>

                    @if($branch->map_url)
                        <div class="mb-0">
                            <label class="text-muted small fw-bold text-uppercase mb-1">Google Maps</label>
                            <div class="mt-2">
                                <a href="{{ $branch->map_url }}" target="_blank" class="btn btn-sm btn-light rounded-pill">
                                    <i class="bi bi-geo-alt-fill me-1"></i> View on Google Maps
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="mt-5 pt-4 border-top d-flex gap-3">
                        <a href="{{ route('partner.branches.edit', $branch) }}" class="btn btn-primary rounded-pill px-4">Edit Branch</a>
                        <form action="{{ route('partner.branches.destroy', $branch) }}" method="POST" onsubmit="return confirm('Delete this branch?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger rounded-pill px-4">Delete Branch</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
