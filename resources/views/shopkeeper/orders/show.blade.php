@extends('layouts.app')

@section('content')
<div class="bg-white p-4 rounded shadow-sm">

    <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">
       <div class="d-flex align-items-center gap-2">
    @if ($order->company?->logo)
        <img src="{{ asset('storage/' . $order->company->logo) }}" alt="Logo" style="height: 45px; width: 45px; object-fit: cover; border-radius: 6px;">
    @endif
    <div>
        <h4 class="mb-0">{{ $order->company?->name ?? 'SalesConnect' }}</h4>
        <div class="text-muted small">Invoice / Order Receipt</div>
    </div>
</div>
        <div class="text-end">
            <h3 class="mb-1">Order #{{ $order->id }}</h3>
            <span class="badge {{ $order->status === 'delivered' ? 'bg-success' : ($order->status === 'cancelled' ? 'bg-danger' : 'bg-warning') }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-6">
            <strong>Distributor:</strong> {{ $order->distributor?->name ?? 'N/A' }}
        </div>
        <div class="col-6 text-end">
            <strong>Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}
        </div>
    </div>

    <table class="table table-bordered mt-3">
        <thead class="table-light">
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product?->name ?? 'Deleted Product' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rs. {{ number_format($item->unit_price, 2) }}</td>
                    <td>Rs. {{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">Total</th>
                <th>Rs. {{ number_format($order->total_amount, 2) }}</th>
            </tr>
        </tfoot>
    </table>

    <a href="{{ route('shopkeeper.orders.index') }}" class="btn btn-outline-secondary">Back to Orders</a>
</div>
@endsection