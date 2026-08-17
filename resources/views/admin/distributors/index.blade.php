@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Manage Distributors</h3>
        <a href="{{ route('admin.distributors.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add New Distributor
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle bg-white">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($distributors as $distributor)
                    <tr>
                        <td>{{ $distributor->name }}</td>
                        <td>{{ $distributor->phone }}</td>
                        <td>{{ $distributor->email }}</td>
                        <td>
                            <span class="badge {{ $distributor->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($distributor->status) }}
                            </span>
                        </td>
                        <td>{{ $distributor->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                            <a href="{{ route('admin.distributors.assignments.index', $distributor) }}" class="btn btn-sm btn-info text-white">
    <i class="bi bi-signpost-split"></i> Assign Shops
</a>
                                <a href="{{ route('admin.distributors.edit', $distributor) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>

                                <form action="{{ route('admin.distributors.reset-password', $distributor) }}" method="POST" onsubmit="return confirm('Generate a new password for this distributor?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-warning">
                                        <i class="bi bi-key"></i> Reset Password
                                    </button>
                                </form>

                                <form action="{{ route('admin.distributors.toggle-status', $distributor) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-secondary">
                                        {{ $distributor->status === 'active' ? 'Suspend' : 'Activate' }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.distributors.destroy', $distributor) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this distributor?')">
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
                        <td colspan="6" class="text-center">No distributors found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $distributors->links() }}

@endsection