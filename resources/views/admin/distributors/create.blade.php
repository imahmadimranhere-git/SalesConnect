@extends('layouts.app')

@section('content')
<div class="bg-white p-4 rounded shadow-sm" style="max-width: 600px;">
    <h3>Add New Distributor</h3>

    <form action="{{ route('admin.distributors.store') }}" method="POST" class="mt-4">
        @csrf

        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <div class="input-group">
                <input type="text" name="email_prefix" value="{{ old('email_prefix') }}" class="form-control @error('email_prefix') is-invalid @enderror" placeholder="e.g. usman-dist">
                <span class="input-group-text">@salesconnect.com</span>
                @error('email_prefix') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror">
            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Create Distributor</button>
        <a href="{{ route('admin.distributors.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </form>
</div>
@endsection