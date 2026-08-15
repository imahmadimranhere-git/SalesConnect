@extends('layouts.app')

@section('content')

    <h3 class="mb-4">Activity Logs</h3>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle bg-white">
            <thead class="table-light">
                <tr>
                    <th>Performed By</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    <tr>
                        <td>{{ $log->user->name ?? 'Deleted User' }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ str_replace('_', ' ', $log->action) }}</span>
                        </td>
                        <td>{{ $log->description }}</td>
                        <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No activity yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $logs->links() }}

@endsection