@extends('layouts.app')

@section('content')
<div class="bg-white p-4 rounded shadow-sm" style="max-width: 650px;">
    <h3>Edit Shopkeeper</h3>

    <form action="{{ route('admin.shopkeepers.update', $shopkeeper) }}" method="POST" class="mt-4">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Shop Name</label>
            <input type="text" name="shop_name" value="{{ old('shop_name', $shopkeeper->shop->name) }}" class="form-control @error('shop_name') is-invalid @enderror">
            @error('shop_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Owner Full Name</label>
            <input type="text" name="owner_name" value="{{ old('owner_name', $shopkeeper->shop->owner_name) }}" class="form-control @error('owner_name') is-invalid @enderror">
            @error('owner_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Address</label>
            <input type="text" name="address" value="{{ old('address', $shopkeeper->shop->address) }}" class="form-control @error('address') is-invalid @enderror">
            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Area</label>
            <input type="text" name="area" value="{{ old('area', $shopkeeper->shop->area) }}" class="form-control @error('area') is-invalid @enderror">
            @error('area') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $shopkeeper->shop->phone) }}" class="form-control @error('phone') is-invalid @enderror">
            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <div class="input-group">
                <input type="text" name="email_prefix" value="{{ old('email_prefix', explode('@', $shopkeeper->email)[0]) }}" class="form-control @error('email_prefix') is-invalid @enderror">
                <span class="input-group-text">@salesconnect.com</span>
                @error('email_prefix') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Shopkeeper</button>
        <a href="{{ route('admin.shopkeepers.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </form>
</div>
@endsection