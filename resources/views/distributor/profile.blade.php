@extends('layouts.app')

@section('content')
<div class="bg-white p-4 rounded shadow-sm" style="max-width: 500px;">
    <h3 class="mb-4">My Profile</h3>

    <div class="mb-3">
        <div class="text-muted small">Full Name</div>
        <div class="fs-6">{{ $user->name }}</div>
    </div>

    <div class="mb-3">
        <div class="text-muted small">Phone</div>
        <div class="fs-6">{{ $user->phone ?? 'N/A' }}</div>
    </div>

    <div class="mb-3">
        <div class="text-muted small">Company</div>
        <div class="fs-6">{{ $user->company?->name ?? 'N/A' }}</div>
    </div>

    <div class="mb-3">
        <div class="text-muted small">Email</div>
        <div class="fs-6">{{ $user->email }}</div>
    </div>

    <div class="mb-3">
        <div class="text-muted small">Status</div>
        <span class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
            {{ ucfirst($user->status) }}
        </span>
    </div>

    <div class="alert alert-light border small mt-4">
        <i class="bi bi-info-circle"></i>
        To update your details or reset your password, please contact your Admin.
    </div>
</div>
@endsection