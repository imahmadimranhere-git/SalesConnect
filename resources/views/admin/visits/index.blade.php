@extends('layouts.app')

@section('content')

    <h3 class="mb-4">Shop Visits</h3>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.visits.index') }}" class="bg-white p-3 rounded shadow-sm mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-12 col-md-4">
                <label class="form-label small">Distributor</label>
                <select name="distributor_id" class="form-select form-select-sm">
                    <option value="">All</option>
                    @foreach ($distributors as $distributor)
                        <option value="{{ $distributor->id }}" {{ request('distributor_id') == $distributor->id ? 'selected' : '' }}>
                            {{ $distributor->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small">From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm">
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small">To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control form-control-sm">
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary w-100">Filter</button>
                <a href="{{ route('admin.visits.index') }}" class="btn btn-sm btn-outline-secondary w-100">Reset</a>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle bg-white">
            <thead class="table-light">
                <tr>
                    <th>Distributor</th>
                    <th>Shop</th>
                    <th>Distance</th>
                    <th>GPS Location</th>
                    <th>Visited At</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($visits as $visit)
                    <tr>
                        <td>{{ $visit->distributor?->name ?? 'N/A' }}</td>
                        <td>{{ $visit->shop?->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-success bg-opacity-10 text-success">
                                {{ number_format($visit->distance_meters, 0) }}m
                            </span>
                        </td>
                        <td>
                            <a href="https://www.google.com/maps?q={{ $visit->latitude }},{{ $visit->longitude }}" target="_blank" class="text-decoration-none small">
                                <i class="bi bi-geo-alt"></i> {{ $visit->latitude }}, {{ $visit->longitude }}
                            </a>
                        </td>
                        <td>{{ $visit->visited_at->format('d M Y, h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No visits recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $visits->links() }}

@endsection