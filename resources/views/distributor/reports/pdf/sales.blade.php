<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: #333; }
        h2 { margin-bottom: 0; }
        .subtitle { color: #666; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f4f6f9; }
        .summary { margin-top: 15px; }
        .summary td { border: none; padding: 4px 0; }
        .summary .label { color: #666; width: 200px; }
        .summary .value { font-weight: bold; font-size: 15px; }
    </style>
</head>
<body>

    <h2>{{ $companyName }}</h2>
    <div class="subtitle">My Sales Report — {{ \Carbon\Carbon::parse($from)->format('d M Y') }} to {{ \Carbon\Carbon::parse($to)->format('d M Y') }}</div>
    <div class="subtitle">Distributor: {{ auth()->user()->name }}</div>

    <table class="summary">
        <tr>
            <td class="label">Total Sales</td>
            <td class="value">Rs. {{ number_format($totalSales, 2) }}</td>
        </tr>
        <tr>
            <td class="label">Delivered Orders</td>
            <td class="value">{{ $totalOrders }}</td>
        </tr>
    </table>

</body>
</html>