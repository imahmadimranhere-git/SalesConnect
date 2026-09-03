<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: #333; }
        h2 { margin-bottom: 0; }
        .subtitle { color: #666; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background-color: #f4f6f9; }
        .summary { margin-top: 15px; }
        .summary td { border: none; padding: 4px 20px 4px 0; }
        .summary .label { color: #666; }
        .summary .value { font-weight: bold; font-size: 15px; }
    </style>
</head>
<body>

    <h2>{{ $companyName }}</h2>
    <div class="subtitle">My Order Report — {{ \Carbon\Carbon::parse($from)->format('d M Y') }} to {{ \Carbon\Carbon::parse($to)->format('d M Y') }}</div>
    <div class="subtitle">Distributor: {{ auth()->user()->name }}</div>

    <table class="summary">
        <tr>
            <td class="label">Pending:</td><td class="value">{{ $pending }}</td>
            <td class="label">Delivered:</td><td class="value">{{ $delivered }}</td>
            <td class="label">Cancelled:</td><td class="value">{{ $cancelled }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Order #</th>
                <th>Shop</th>
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
                    <td>Rs. {{ number_format($order->total_amount, 2) }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                    <td>{{ $order->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="5">No orders in this date range.</td></tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>