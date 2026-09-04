<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: #333; }
        .header { overflow: hidden; margin-bottom: 20px; }
        .header h2 { margin: 0; float: left; }
        .header .status { float: right; font-weight: bold; padding: 4px 10px; border-radius: 4px; }
        .status-delivered { background-color: #d1e7dd; color: #0f5132; }
        .status-cancelled { background-color: #f8d7da; color: #842029; }
        .status-pending { background-color: #fff3cd; color: #664d03; }
        .subtitle { color: #666; clear: both; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f4f6f9; }
        .info-table { margin-top: 15px; margin-bottom: 5px; }
        .info-table td { border: none; padding: 3px 0; }
        .info-table .label { color: #666; width: 120px; }
        tfoot td { font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h2>{{ $companyName }}</h2>
        <span class="status status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
    </div>
    <div class="subtitle">Order Receipt — Order #{{ $order->id }}</div>

    <table class="info-table">
        <tr>
            <td class="label">Shop:</td>
            <td>{{ $order->shop?->name }} ({{ $order->shop?->area }})</td>
        </tr>
        <tr>
            <td class="label">Distributor:</td>
            <td>{{ $order->distributor?->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Date:</td>
            <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
        </tr>
    </table>

    <table>
        <thead>
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
                <td colspan="3" style="text-align: right;">Total</td>
                <td>Rs. {{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </tfoot>
    </table>

</body>
</html>