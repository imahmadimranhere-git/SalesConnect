@extends('layouts.app')

@section('content')
<div class="bg-white p-4 rounded shadow-sm" style="max-width: 650px;">
    <h3>Add New Product</h3>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="mt-4">
        @csrf

        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="col-12 col-md-6 mb-3">
                <label class="form-label">SKU</label>
                <input type="text" name="sku" value="{{ old('sku') }}" class="form-control @error('sku') is-invalid @enderror" placeholder="e.g. PRD-001">
                @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12 col-md-6 mb-3">
                <label class="form-label">Category</label>
                <input type="text" name="category" value="{{ old('category') }}" class="form-control @error('category') is-invalid @enderror">
                @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6 mb-3">
                <label class="form-label">Price (Rs.)</label>
                <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="form-control @error('price') is-invalid @enderror">
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12 col-md-6 mb-3">
                <label class="form-label">Stock Quantity</label>
                <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" class="form-control @error('stock_quantity') is-invalid @enderror">
                @error('stock_quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Product Image</label>
            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Create Product</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </form>
</div>
@endsection