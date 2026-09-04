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
use App\Models\ShopAssignment;

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
    $assignedShopIds = ShopAssignment::where('distributor_id', auth()->id())
        ->pluck('shop_id')
        ->unique();

    $shops = Shop::whereIn('id', $assignedShopIds)
        ->orderBy('name')
        ->get(['id', 'name', 'area']);

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

        // Check stock availability BEFORE creating anything.
        foreach ($validated['products'] as $item) {
            $product = Product::find($item['id']);

            if ($product && $item['quantity'] > $product->stock_quantity) {
                return back()
                    ->withInput()
                    ->with('error', "Not enough stock for \"{$product->name}\". Available: {$product->stock_quantity}, requested: {$item['quantity']}.");
            }
        }

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

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        abort_if($order->distributor_id !== auth()->id(), 403);

        // Once an order is delivered or cancelled, it becomes final — no further changes allowed.
        if (in_array($order->status, ['delivered', 'cancelled'])) {
            return redirect()
                ->route('distributor.orders.index')
                ->with('error', 'This order is already finalized and cannot be changed.');
        }

        $request->validate([
            'status' => ['required', 'in:pending,delivered,cancelled'],
        ]);

        DB::transaction(function () use ($order, $request) {
            $order->update(['status' => $request->status]);

            if ($request->status === 'delivered') {
                foreach ($order->items as $item) {
                    $item->product->decrement('stock_quantity', $item->quantity);
                }
            }
        });

        return redirect()
            ->route('distributor.orders.index')
            ->with('success', 'Order status updated successfully.');
    }
}