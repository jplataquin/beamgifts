@extends('layouts.app')

@section('title', 'Edit Branch Manager')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 p-4">
                <div class="card-body">
                    <h1 class="h4 fw-bold mb-4 text-primary">Edit Branch Manager</h1>
                    
                    <form action="{{ route('partner.managers.update', $manager) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Assigned Branch</label>
                            <select name="branch_id" class="form-select rounded-pill @error('branch_id') is-invalid @enderror" required>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ (old('branch_id', $manager->branch_id) == $branch->id) ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            @error('branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Full Name</label>
                            <input type="text" name="name" class="form-control rounded-pill @error('name') is-invalid @enderror" value="{{ old('name', $manager->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Email Address</label>
                            <input type="email" name="email" class="form-control rounded-pill @error('email') is-invalid @enderror" value="{{ old('email', $manager->email) }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="alert alert-info small rounded-4 border-0 mb-4">
                            <i class="bi bi-info-circle me-2"></i>Leave password fields blank to keep the current password.
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted">New Password</label>
                                <input type="password" name="password" class="form-control rounded-pill @error('password') is-invalid @enderror">
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label small fw-bold text-muted">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control rounded-pill">
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold">Update Manager Account</button>
                            <a href="{{ route('partner.managers.index') }}" class="btn btn-light rounded-pill py-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection