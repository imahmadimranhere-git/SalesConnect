@extends('layouts.app')

@section('content')

    <h3 class="mb-4">Manage Orders</h3>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.orders.index') }}" class="bg-white p-3 rounded shadow-sm mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-12 col-md-3">
                <label class="form-label small">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="col-12 col-md-3">
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

            <div class="col-6 col-md-2">
                <label class="form-label small">From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm">
            </div>

            <div class="col-6 col-md-2">
                <label class="form-label small">To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control form-control-sm">
            </div>

            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary w-100">Filter</button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary w-100">Reset</a>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle bg-white">
            <thead class="table-light">
                <tr>
                    <th>Order #</th>
                    <th>Shop</th>
                    <th>Distributor</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->shop?->name ?? 'N/A' }}</td>
                        <td>{{ $order->distributor?->name ?? 'N/A' }}</td>
                        <td>{{ $order->items->count() }} item(s)</td>
                        <td>Rs. {{ number_format($order->total_amount, 2) }}</td>
                        <td>
                            <span class="badge
                                {{ $order->status === 'delivered' ? 'bg-success' : ($order->status === 'cancelled' ? 'bg-danger' : 'bg-warning') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex flex-column gap-2">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> View
                                </a>

                                @if ($order->status === 'pending')
                                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="d-flex gap-1">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="form-select form-select-sm" style="width: auto;">
                                            <option value="pending" selected>Pending</option>
                                            <option value="delivered">Delivered</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                    </form>
                                @else
                                    <span class="text-muted small"><i class="bi bi-lock"></i> Finalized</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $orders->links() }}

@endsection