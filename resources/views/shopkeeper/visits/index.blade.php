@extends('layouts.app')

@section('content')

    <h3 class="mb-4">Visit History</h3>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle bg-white">
            <thead class="table-light">
                <tr>
                    <th>Distributor</th>
                    <th>Visited At</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($visits as $visit)
                    <tr>
                        <td>{{ $visit->distributor?->name ?? 'N/A' }}</td>
                        <td>{{ $visit->visited_at->format('d M Y, h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center">No visits recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $visits->links() }}

@endsection