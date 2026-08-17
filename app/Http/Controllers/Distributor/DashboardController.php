<?php

namespace App\Http\Controllers\Distributor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ShopAssignment;
use App\Models\Visit;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $distributorId = auth()->id();
        $today = strtolower(now()->format('l')); // e.g. "monday"

        $todaysAssignments = ShopAssignment::with('shop')
            ->where('distributor_id', $distributorId)
            ->where('day_of_week', $today)
            ->orderBy('visit_order')
            ->get();

        $visitedShopIdsToday = Visit::where('distributor_id', $distributorId)
            ->whereDate('visited_at', today())
            ->pluck('shop_id');

        $totalAssignedShops = $todaysAssignments->count();
        $visitedToday = $visitedShopIdsToday->count();
        $pendingVisits = $totalAssignedShops - $visitedToday;

        $ordersToday = Order::where('distributor_id', $distributorId)
            ->whereDate('created_at', today())
            ->count();

        $salesToday = Order::where('distributor_id', $distributorId)
            ->whereDate('created_at', today())
            ->sum('total_amount');

        return view('distributor.dashboard', compact(
            'todaysAssignments',
            'visitedShopIdsToday',
            'totalAssignedShops',
            'visitedToday',
            'pendingVisits',
            'ordersToday',
            'salesToday',
        ));
    }
}