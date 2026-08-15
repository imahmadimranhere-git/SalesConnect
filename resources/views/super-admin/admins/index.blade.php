@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Manage Admins</h3>
        <a href="{{ route('super-admin.admins.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add New Admin
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle bg-white">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Company</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($admins as $admin)
                    <tr>
                        <td>{{ $admin->name }}</td>
                        <td>{{ $admin->company->name }}</td>
                        <td>{{ $admin->email }}</td>
                        <td>
                            <span class="badge {{ $admin->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($admin->status) }}
                            </span>
                        </td>
                        <td>{{ $admin->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                <a href="{{ route('super-admin.admins.edit', $admin) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>

                                <form action="{{ route('super-admin.admins.reset-password', $admin) }}" method="POST" onsubmit="return confirm('Generate a new password for this admin?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-warning">
                                        <i class="bi bi-key"></i> Reset Password
                                    </button>
                                </form>

                                <form action="{{ route('super-admin.admins.toggle-status', $admin) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-secondary">
                                        {{ $admin->status === 'active' ? 'Suspend' : 'Activate' }}
                                    </button>
                                </form>

                                <form action="{{ route('super-admin.admins.destroy', $admin) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this admin?')">
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
                        <td colspan="6" class="text-center">No admins found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $admins->links() }}

@endsection
