@extends('layouts.app')

@section('content')
<div class="bg-white p-4 rounded shadow-sm" style="max-width: 600px;">
    <h3>System Settings</h3>
    <p class="text-muted small">This name and logo will appear in the browser tab and in the Super Admin sidebar.</p>

    <form action="{{ route('super-admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="mt-4">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">App Name</label>
            <input type="text" name="app_name" value="{{ old('app_name', $setting->app_name) }}" class="form-control @error('app_name') is-invalid @enderror">
            @error('app_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Logo</label>
            <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror">
            @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror

            @if ($setting->logo)
                <img src="{{ asset('storage/' . $setting->logo) }}" alt="Current Logo" class="mt-2 d-block" style="height: 60px;">
            @endif
        </div>

        <button type="submit" class="btn btn-primary mt-2">Save Settings</button>
    </form>
</div>
@endsection