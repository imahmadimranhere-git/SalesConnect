@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>My Orders</h3>
        <a href="{{ route('distributor.orders.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Create New Order
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle bg-white">
            <thead class="table-light">
                <tr>
                    <th>Order #</th>
                    <th>Shop</th>
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
                        <td>{{ $order->items->count() }} item(s)</td>
                        <td>Rs. {{ number_format($order->total_amount, 2) }}</td>
                        <td>
                            <span class="badge {{ $order->status === 'delivered' ? 'bg-success' : ($order->status === 'cancelled' ? 'bg-danger' : 'bg-warning') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('distributor.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No orders yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $orders->links() }}

@endsection