@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Manage Shops</h3>
        <a href="{{ route('admin.shops.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add New Shop
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle bg-white">
            <thead class="table-light">
                <tr>
                    <th>Shop Name</th>
                    <th>Owner</th>
                    <th>Area</th>
                    <th>Location</th>
                    <th>Status</th>
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
                            <div class="d-flex gap-1 flex-wrap">
                                <a href="{{ route('admin.shops.edit', $shop) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('admin.shops.toggle-status', $shop) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-secondary">
                                        {{ $shop->status === 'active' ? 'Suspend' : 'Activate' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.shops.destroy', $shop) }}" method="POST" onsubmit="return confirm('Are you sure?')">
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
                        <td colspan="6" class="text-center">No shops found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $shops->links() }}

@endsection