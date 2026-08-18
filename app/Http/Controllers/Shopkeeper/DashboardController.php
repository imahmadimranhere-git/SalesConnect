<?php

namespace App\Http\Controllers\Shopkeeper;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Visit;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $shop = auth()->user()->shop;

        $totalOrdersThisMonth = Order::where('shop_id', $shop?->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $pendingOrders = Order::where('shop_id', $shop?->id)
            ->where('status', 'pending')
            ->count();

        $totalAmountThisMonth = Order::where('shop_id', $shop?->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        $lastVisit = Visit::where('shop_id', $shop?->id)
            ->with('distributor')
            ->latest('visited_at')
            ->first();

        return view('shopkeeper.dashboard', compact(
            'shop',
            'totalOrdersThisMonth',
            'pendingOrders',
            'totalAmountThisMonth',
            'lastVisit',
        ));
    }
}