@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Manage Shopkeepers</h3>
        <a href="{{ route('admin.shopkeepers.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add New Shopkeeper
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle bg-white">
            <thead class="table-light">
                <tr>
                    <th>Shop Name</th>
                    <th>Owner Name</th>
                    <th>Area</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($shopkeepers as $shopkeeper)
                    <tr>
                        <td>{{ $shopkeeper->shop?->name ?? 'N/A' }}</td>
<td>{{ $shopkeeper->shop?->owner_name ?? 'N/A' }}</td>
<td>{{ $shopkeeper->shop?->area ?? 'N/A' }}</td>
                        <td>{{ $shopkeeper->email }}</td>
                        <td>
                            <span class="badge {{ $shopkeeper->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($shopkeeper->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                <a href="{{ route('admin.shopkeepers.edit', $shopkeeper) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>

                                <form action="{{ route('admin.shopkeepers.reset-password', $shopkeeper) }}" method="POST" onsubmit="return confirm('Generate a new password?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-warning">
                                        <i class="bi bi-key"></i> Reset Password
                                    </button>
                                </form>

                                <form action="{{ route('admin.shopkeepers.toggle-status', $shopkeeper) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-secondary">
                                        {{ $shopkeeper->status === 'active' ? 'Suspend' : 'Activate' }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.shopkeepers.destroy', $shopkeeper) }}" method="POST" onsubmit="return confirm('Are you sure?')">
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
                        <td colspan="6" class="text-center">No shopkeepers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $shopkeepers->links() }}

@endsection