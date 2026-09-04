@extends('layouts.app')

@section('content')

    <h3 class="mb-4">Product Catalog</h3>

    <form method="GET" class="mb-4" style="max-width: 400px;">
        <div class="input-group">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search products...">
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
        </div>
    </form>

    <div class="row g-3">
        @forelse ($products as $product)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card shadow-sm border-0 h-100">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" style="height: 120px; object-fit: cover;" alt="{{ $product->name }}">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 120px;">
                            <i class="bi bi-box-seam fs-1 text-muted"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h6 class="mb-1">{{ $product->name }}</h6>
                        <div class="text-muted small mb-1">{{ $product->category ?? 'Uncategorized' }}</div>
                        <div class="fw-bold">Rs. {{ number_format($product->price, 2) }}</div>
                        <span class="badge {{ $product->stock_quantity > 0 ? 'bg-success' : 'bg-danger' }} bg-opacity-10 {{ $product->stock_quantity > 0 ? 'text-success' : 'text-danger' }}">
                            {{ $product->stock_quantity > 0 ? $product->stock_quantity . ' in stock' : 'Out of stock' }}
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">No products found.</p>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>

@endsection