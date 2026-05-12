@extends('layouts.app')

@section('title', 'Branch Managers')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-0 text-primary">Branch Managers</h1>
            <p class="text-muted">Manage accounts for your branch staff.</p>
        </div>
        <a href="{{ route('partner.managers.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-lg me-2"></i>Add Manager
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-pill px-4 mb-4 border-0 shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Manager Name</th>
                        <th class="py-3">Email</th>
                        <th class="py-3 pe-4">Branch</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($managers as $manager)
                        <tr onclick="window.location='{{ route('partner.managers.show', $manager) }}'" style="cursor: pointer;">
                            <td class="ps-4 fw-bold text-dark">{{ $manager->name }}</td>
                            <td>{{ $manager->email }}</td>
                            <td class="pe-4">
                                <span class="badge bg-info-subtle text-info rounded-pill px-3">{{ $manager->branch->name }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">No branch managers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection