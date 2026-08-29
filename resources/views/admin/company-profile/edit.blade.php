@extends('layouts.app')

@section('content')
<div class="bg-white p-4 rounded shadow-sm" style="max-width: 600px;">
    <h3>Company Profile</h3>
    <p class="text-muted small">This name and logo will appear across your Distributor and Shopkeeper panels.</p>

    <form action="{{ route('admin.company-profile.update') }}" method="POST" enctype="multipart/form-data" class="mt-4">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Company Name</label>
            <input type="text" name="name" value="{{ old('name', $company->name) }}" class="form-control @error('name') is-invalid @enderror">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Company Logo</label>
            <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror">
            @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror

            @if ($company->logo)
                <img src="{{ asset('storage/' . $company->logo) }}" alt="Current Logo" class="mt-2 d-block rounded" style="height: 60px;">
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>
@endsection