@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Manage Products</h3>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add New Product
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle bg-white">
            <thead class="table-light">
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>SKU</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="height: 40px; width: 40px; object-fit: cover;" class="rounded">
                            @else
                                <span class="text-muted small">No image</span>
                            @endif
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->category ?? '-' }}</td>
                        <td>Rs. {{ number_format($product->price, 2) }}</td>
                        <td>
                            <span class="badge {{ $product->stock_quantity > 0 ? 'bg-success' : 'bg-danger' }} bg-opacity-10 {{ $product->stock_quantity > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $product->stock_quantity }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $product->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($product->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('admin.products.toggle-status', $product) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-secondary">
                                        {{ $product->status === 'active' ? 'Suspend' : 'Activate' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $products->links() }}

@endsection