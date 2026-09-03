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
    <a href="{{ route('admin.reports.orders.export', ['date_from' => $from, 'date_to' => $to]) }}" class="btn btn-sm btn-outline-danger w-100">
        <i class="bi bi-file-earmark-pdf"></i> Export PDF
    </a>
</div>
        </div>
    </form>

    <div class="row g-3 mb-4">
        <div class="col-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Pending</div>
                    <div class="fs-4 fw-bold text-warning">{{ $pending }}</div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Delivered</div>
                    <div class="fs-4 fw-bold text-success">{{ $delivered }}</div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Cancelled</div>
                    <div class="fs-4 fw-bold text-danger">{{ $cancelled }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle bg-white">
            <thead class="table-light">
                <tr>
                    <th>Order #</th>
                    <th>Shop</th>
                    <th>Distributor</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->shop?->name ?? 'N/A' }}</td>
                        <td>{{ $order->distributor?->name ?? 'N/A' }}</td>
                        <td>Rs. {{ number_format($order->total_amount, 2) }}</td>
                        <td>
                            <span class="badge {{ $order->status === 'delivered' ? 'bg-success' : ($order->status === 'cancelled' ? 'bg-danger' : 'bg-warning') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No orders in this date range.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $orders->links() }}

@endsection