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
        </div>
    </form>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Total Sales</div>
                    <div class="fs-3 fw-bold text-success">Rs. {{ number_format($totalSales, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Delivered Orders</div>
                    <div class="fs-3 fw-bold">{{ $totalOrders }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle bg-white">
            <thead class="table-light">
                <tr>
                    <th>Distributor</th>
                    <th>Orders</th>
                    <th>Total Sales</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($byDistributor as $row)
                    <tr>
                        <td>{{ $row['name'] }}</td>
                        <td>{{ $row['orders'] }}</td>
                        <td>Rs. {{ number_format($row['total'], 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No sales in this date range.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection