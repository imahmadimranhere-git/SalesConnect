<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request): View
{
    $orders = Order::with(['shop', 'distributor', 'items.product'])
        ->when($request->status, function ($query, $status) {
            $query->where('status', $status);
        })
        ->when($request->distributor_id, function ($query, $distributorId) {
            $query->where('distributor_id', $distributorId);
        })
        ->when($request->date_from, function ($query, $date) {
            $query->whereDate('created_at', '>=', $date);
        })
        ->when($request->date_to, function ($query, $date) {
            $query->whereDate('created_at', '<=', $date);
        })
        ->orderBy('id', 'asc')
        ->paginate(10)
        ->withQueryString();

    $distributors = \App\Models\User::where('company_id', auth()->user()->company_id)
        ->where('role', 'distributor')
        ->get(['id', 'name']);

    return view('admin.orders.index', compact('orders', 'distributors'));
}

    public function updateStatus(Request $request, Order $order): RedirectResponse
{
    $request->validate([
        'status' => ['required', 'in:pending,delivered,cancelled'],
    ]);

    $newStatus = $request->status;
    $wasAlreadyDelivered = $order->status === 'delivered';

    DB::transaction(function () use ($order, $newStatus, $wasAlreadyDelivered) {
        $order->update(['status' => $newStatus]);

        // Only deduct stock the FIRST time an order becomes "delivered".
        if ($newStatus === 'delivered' && ! $wasAlreadyDelivered) {
            foreach ($order->items as $item) {
                $item->product->decrement('stock_quantity', $item->quantity);
            }
        }
    });

    return redirect()
        ->route('admin.orders.index')
        ->with('success', 'Order status updated successfully.');
}

public function show(Order $order): View
{
    $order->load(['shop', 'distributor', 'items.product', 'company']);

    return view('admin.orders.show', compact('order'));
}
}