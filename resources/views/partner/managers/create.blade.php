@extends('layouts.app')

@section('title', 'Add Branch Manager')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 p-4">
                <div class="card-body">
                    <h1 class="h4 fw-bold mb-4 text-primary">Add Branch Manager</h1>
                    
                    <form action="{{ route('partner.managers.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Assigned Branch</label>
                            <select name="branch_id" class="form-select rounded-pill @error('branch_id') is-invalid @enderror" required>
                                <option value="">Select a branch...</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            @error('branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Full Name</label>
                            <input type="text" name="name" class="form-control rounded-pill @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Email Address</label>
                            <input type="email" name="email" class="form-control rounded-pill @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted">Password</label>
                                <input type="password" name="password" class="form-control rounded-pill @error('password') is-invalid @enderror" required>
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label small fw-bold text-muted">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control rounded-pill" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold">Create Manager Account</button>
                            <a href="{{ route('partner.managers.index') }}" class="btn btn-light rounded-pill py-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection