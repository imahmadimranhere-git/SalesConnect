@extends('layouts.app')

@section('content')
<div class="bg-white p-4 rounded shadow-sm" style="max-width: 500px;">
    <h3 class="mb-4">My Shop Profile</h3>

    <div class="mb-3">
        <div class="text-muted small">Shop Name</div>
        <div class="fs-6">{{ $shop?->name ?? 'N/A' }}</div>
    </div>

    <div class="mb-3">
        <div class="text-muted small">Address</div>
        <div class="fs-6">{{ $shop?->address ?? 'N/A' }}, {{ $shop?->area ?? '' }}</div>
    </div>

    <div class="mb-3">
        <div class="text-muted small">Company</div>
        <div class="fs-6">{{ $user->company?->name ?? 'N/A' }}</div>
    </div>

    <div class="mb-3">
        <div class="text-muted small">Email</div>
        <div class="fs-6">{{ $user->email }}</div>
    </div>

    <div class="alert alert-light border small mt-4">
        <i class="bi bi-info-circle"></i>
        To update your details or reset your password, please contact your Admin.
    </div>
</div>
@endsection