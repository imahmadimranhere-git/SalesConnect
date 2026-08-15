@extends('layouts.app')

@section('content')
<div class="bg-white p-4 rounded shadow-sm" style="max-width: 600px;">
    <h3>Edit Admin</h3>

    <form action="{{ route('super-admin.admins.update', $admin) }}" method="POST" class="mt-4">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Company Name</label>
            <input type="text" name="company_name" value="{{ old('company_name', $admin->company->name) }}" class="form-control @error('company_name') is-invalid @enderror">
            @error('company_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Owner Full Name</label>
            <input type="text" name="owner_name" value="{{ old('owner_name', $admin->name) }}" class="form-control @error('owner_name') is-invalid @enderror">
            @error('owner_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <div class="input-group">
                <input type="text" name="email_prefix" value="{{ old('email_prefix', explode('@', $admin->email)[0]) }}" class="form-control @error('email_prefix') is-invalid @enderror">
                <span class="input-group-text">@salesconnect.com</span>
                @error('email_prefix') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $admin->phone) }}" class="form-control @error('phone') is-invalid @enderror">
            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update Admin</button>
        <a href="{{ route('super-admin.admins.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </form>
</div>
@endsection
