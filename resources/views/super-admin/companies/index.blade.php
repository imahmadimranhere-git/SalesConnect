@extends('layouts.app')

@section('content')

    <h3 class="mb-4">Companies Management</h3>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle bg-white">
            <thead class="table-light">
                <tr>
                    <th>Company Name</th>
                    <th>Status</th>
                    <th>Distributors</th>
                    <th>Shopkeepers</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($companies as $company)
                    <tr>
                        <td>{{ $company->name }}</td>
                        <td>
                            <span class="badge {{ $company->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($company->status) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                {{ $company->distributors_count }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-info">
                                {{ $company->shopkeepers_count }}
                            </span>
                        </td>
                        <td>{{ $company->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No companies found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $companies->links() }}

@endsection