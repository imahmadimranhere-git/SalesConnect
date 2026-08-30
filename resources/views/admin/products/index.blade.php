@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h3 class="mb-0">Manage Products</h3>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add New Product
        </a>
    </div>

    {{-- Search --}}
    <form method="GET" class="mb-3">
        <div class="input-group" style="max-width: 400px;">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by product name...">
            <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
            @if (request('search'))
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Clear</a>
            @endif
        </div>
    </form>

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
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-gear"></i> Actions
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.products.edit', $product) }}">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.products.toggle-status', $product) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-toggle2-on"></i> {{ $product->status === 'active' ? 'Suspend' : 'Activate' }}
                                            </button>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
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