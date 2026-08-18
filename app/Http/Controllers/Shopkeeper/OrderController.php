<?php

namespace App\Http\Controllers\Shopkeeper;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $shop = auth()->user()->shop;

        $orders = Order::with(['distributor', 'items.product'])
            ->where('shop_id', $shop?->id)
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('shopkeeper.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        abort_if($order->shop_id !== auth()->user()->shop_id, 403);

        $order->load(['items.product', 'company']);

        return view('shopkeeper.orders.show', compact('order'));
    }
}