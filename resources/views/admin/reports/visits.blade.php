@extends('layouts.app')

@section('content')

    <h3 class="mb-4">Reports</h3>
    @include('admin.reports._tabs')

    <form method="GET" class="bg-white p-3 rounded shadow-sm mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-6 col-md-3">
                <label class="form-label small">From</label>
                <input type="date" name="date_from" value="{{ $from }}" class="form-control form-control-sm">
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small">To</label>
                <input type="date" name="date_to" value="{{ $to }}" class="form-control form-control-sm">
            </div>
            <div class="col-12 col-md-2">
    <button type="submit" class="btn btn-sm btn-primary w-100">Filter</button>
</div>
<div class="col-12 col-md-2">
    <a href="{{ route('admin.reports.visits.export', ['date_from' => $from, 'date_to' => $to]) }}" class="btn btn-sm btn-outline-danger w-100">
        <i class="bi bi-file-earmark-pdf"></i> Export PDF
    </a>
</div>
        </div>
    </form>

    <div class="card shadow-sm border-0 mb-4" style="max-width: 300px;">
        <div class="card-body">
            <div class="text-muted small">Total Visits</div>
            <div class="fs-3 fw-bold">{{ $totalVisits }}</div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle bg-white">
            <thead class="table-light">
                <tr>
                    <th>Distributor</th>
                    <th>Total Visits</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($byDistributor as $row)
                    <tr>
                        <td>{{ $row['name'] }}</td>
                        <td>{{ $row['visits'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center">No visits in this date range.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection