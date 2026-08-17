<?php

namespace App\Http\Controllers\Distributor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
{
    $orders = Order::with(['shop', 'items.product'])
        ->where('distributor_id', auth()->id())
        ->orderBy('id', 'asc')
        ->paginate(10);

    return view('distributor.orders.index', compact('orders'));
}

    public function create(): View
    {
        $shops = Shop::orderBy('name')->get(['id', 'name', 'area']);
        $products = Product::where('status', 'active')->orderBy('name')->get(['id', 'name', 'price', 'stock_quantity']);

        return view('distributor.orders.create', compact('shops', 'products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'shop_id' => ['required', 'exists:shops,id'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.id' => ['required', 'exists:products,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $order = DB::transaction(function () use ($validated) {
            $order = Order::create([
                'shop_id' => $validated['shop_id'],
                'distributor_id' => auth()->id(),
                'total_amount' => 0,
                'status' => 'pending',
            ]);

            $total = 0;

            foreach ($validated['products'] as $item) {
                $product = Product::findOrFail($item['id']);
                $subtotal = $product->price * $item['quantity'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;
            }

            $order->update(['total_amount' => $total]);

            return $order;
        });

        return redirect()
            ->route('distributor.orders.index')
            ->with('success', "Order #{$order->id} created successfully.");
    }

    public function show(Order $order): View
{
    abort_if($order->distributor_id !== auth()->id(), 403);

    $order->load(['shop', 'items.product', 'company']);

    return view('distributor.orders.show', compact('order'));
}
}