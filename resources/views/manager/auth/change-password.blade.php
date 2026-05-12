@extends('layouts.app')

@section('title', 'Change Password')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white text-center py-4 border-0">
                    <h1 class="h3 fw-bold mb-1 text-primary">Change Your Password</h1>
                    <p class="text-muted small mb-0">For security reasons, you must change your password before continuing.</p>
                </div>
                <div class="card-body px-4 px-md-5 pb-5">
                    @if ($errors->any())
                        <div class="alert alert-danger rounded-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('manager.password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="current_password" class="form-label fw-bold">Current Password</label>
                            <input id="current_password" type="password" class="form-control" name="current_password" required autofocus>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold">New Password</label>
                            <input id="password" type="password" class="form-control" name="password" required>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-bold">Confirm New Password</label>
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
