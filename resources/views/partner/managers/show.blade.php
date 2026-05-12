@extends('layouts.app')

@section('title', 'Manager Details')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('partner.managers.index') }}" class="btn btn-light rounded-pill me-3">
                    &larr; Back
                </a>
                <h1 class="h3 fw-bold mb-0 text-primary">Manager Details</h1>
            </div>

            <div class="card shadow-sm border-0 p-4 rounded-4">
                <div class="card-body">
                    <div class="mb-4">
                        <label class="text-muted small fw-bold text-uppercase mb-1">Manager Name</label>
                        <p class="h4 fw-bold text-dark">{{ $manager->name }}</p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="text-muted small fw-bold text-uppercase mb-1">Email Address</label>
                            <p>{{ $manager->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small fw-bold text-uppercase mb-1">Security Status</label>
                            <p>
                                @if($manager->must_change_password)
                                    <span class="badge bg-warning text-dark rounded-pill">Pending Password Change</span>
                                @else
                                    <span class="badge bg-success rounded-pill">Password Active</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted small fw-bold text-uppercase mb-1">Assigned Branch</label>
                        <p><span class="badge bg-info-subtle text-info rounded-pill px-3">{{ $manager->branch->name }}</span></p>
                    </div>

                    <div class="mt-5 pt-4 border-top d-flex gap-3">
                        <a href="{{ route('partner.managers.edit', $manager) }}" class="btn btn-primary rounded-pill px-4">Edit Manager</a>
                        <form action="{{ route('partner.managers.destroy', $manager) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this manager?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger rounded-pill px-4">Delete Manager</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
