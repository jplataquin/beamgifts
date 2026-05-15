@extends('layouts.app')

@section('title', 'Settings - Beam Gifts')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            @include('partials.account-menu')
        </div>
        <div class="col-md-9">
            <h1 class="h3 fw-bold mb-4 text-primary">Settings</h1>
            
            @if(session('success'))
                <div class="alert alert-success rounded-pill px-4 mb-4">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger rounded-4 mb-4">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm border-0 p-4 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">Preferences</h5>
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="default_city_id" class="form-label small fw-bold">Default City</label>
                            <select name="default_city_id" id="default_city_id" class="form-select bg-light border-0">
                                <option value="">None</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ $gifter->default_city_id == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text small text-muted">Selecting a default city will help us personalize your experience.</div>
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Save Preferences</button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0 p-4 border-start border-4 border-danger">
                <div class="card-body">
                    <h5 class="fw-bold mb-3 text-danger">Danger Zone</h5>
                    <p class="text-muted small mb-4">
                        Deactivate your account. You will lose access to all your past orders and digital gifts.
                    </p>
                    <button type="button" class="btn btn-outline-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#deactivateModal">
                        Deactivate Account
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Deactivate Account Modal -->
<div class="modal fade" id="deactivateModal" tabindex="-1" aria-labelledby="deactivateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title fw-bold" id="deactivateModalLabel">Confirm Deactivation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-0">Are you sure you want to deactivate your account? You will be logged out and your data will no longer be accessible.</p>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('settings.deactivate') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-pill px-4">Yes, Deactivate</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
