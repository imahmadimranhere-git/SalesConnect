@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h3 class="mb-0">Manage Shops</h3>
        <a href="{{ route('admin.shops.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add New Shop
        </a>
    </div>

    {{-- Search --}}
    <form method="GET" class="mb-3">
        <div class="input-group" style="max-width: 400px;">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by shop name...">
            <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
            @if (request('search'))
                <a href="{{ route('admin.shops.index') }}" class="btn btn-outline-secondary">Clear</a>
            @endif
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle bg-white">
            <thead class="table-light">
                <tr>
                    <th>Shop Name</th>
                    <th>Owner</th>
                    <th>Area</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Login</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($shops as $shop)
                    <tr>
                        <td>{{ $shop->name }}</td>
                        <td>{{ $shop->owner_name }}</td>
                        <td>{{ $shop->area }}</td>
                        <td>
                            <a href="https://www.google.com/maps?q={{ $shop->latitude }},{{ $shop->longitude }}" target="_blank" class="text-decoration-none">
                                <i class="bi bi-geo-alt"></i> View on Map
                            </a>
                        </td>
                        <td>
                            <span class="badge {{ $shop->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($shop->status) }}
                            </span>
                        </td>
                        <td>
                            @if ($shop->shopkeeper)
                                <span class="badge bg-success bg-opacity-10 text-success">{{ $shop->shopkeeper->email }}</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary">No Login</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-gear"></i> Actions
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.shops.edit', $shop) }}">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.shops.toggle-status', $shop) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-toggle2-on"></i> {{ $shop->status === 'active' ? 'Suspend' : 'Activate' }}
                                            </button>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.shops.destroy', $shop) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this shop?')">
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
                        <td colspan="7" class="text-center">No shops found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $shops->links() }}

@endsection