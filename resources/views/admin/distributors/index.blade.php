@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h3 class="mb-0">Manage Distributors</h3>
        <a href="{{ route('admin.distributors.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add New Distributor
        </a>
    </div>

    {{-- Search --}}
    <form method="GET" class="mb-3">
        <div class="input-group" style="max-width: 400px;">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by name or email...">
            <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
            @if (request('search'))
                <a href="{{ route('admin.distributors.index') }}" class="btn btn-outline-secondary">Clear</a>
            @endif
        </div>
    </form>

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
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-gear"></i> Actions
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.distributors.assignments.index', $distributor) }}">
                                            <i class="bi bi-signpost-split"></i> Assign Shops
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.distributors.edit', $distributor) }}">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.distributors.reset-password', $distributor) }}" method="POST" onsubmit="return confirm('Generate a new password for this distributor?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-key"></i> Reset Password
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.distributors.toggle-status', $distributor) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-toggle2-on"></i> {{ $distributor->status === 'active' ? 'Suspend' : 'Activate' }}
                                            </button>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.distributors.destroy', $distributor) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this distributor?')">
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
                        <td colspan="6" class="text-center">No distributors found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $distributors->links() }}

@endsection