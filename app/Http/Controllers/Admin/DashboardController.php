<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shop;
use App\Models\ShopAssignment;
use App\Models\User;
use App\Models\Visit;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $companyId = auth()->user()->company_id;

        $totalDistributors = User::where('company_id', $companyId)
            ->where('role', 'distributor')
            ->count();

        $totalShopkeepers = User::where('company_id', $companyId)
            ->where('role', 'shopkeeper')
            ->count();

        $totalShops = Shop::count();

        $ordersToday = Order::whereDate('created_at', today())->count();

        $today = strtolower(now()->format('l'));

        $totalAssignedToday = ShopAssignment::where('company_id', $companyId)
            ->where('day_of_week', $today)
            ->count();

        $visitedToday = Visit::where('company_id', $companyId)
            ->whereDate('visited_at', today())
            ->count();

        $pendingVisits = max($totalAssignedToday - $visitedToday, 0);

        $todaySales = Order::where('status', 'delivered')
            ->whereDate('created_at', today())
            ->sum('total_amount');

        $monthlySales = Order::where('status', 'delivered')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        $yearlySales = Order::where('status', 'delivered')
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        // Orders still "pending" from last month or earlier — these need admin's attention.
        $oldPendingOrders = Order::where('status', 'pending')
            ->where('created_at', '<', now()->startOfMonth())
            ->count();

        $oldPendingCutoffDate = now()->subMonthNoOverflow()->endOfMonth()->toDateString();

        return view('admin.dashboard', compact(
            'totalDistributors',
            'totalShopkeepers',
            'totalShops',
            'visitedToday',
            'pendingVisits',
            'ordersToday',
            'todaySales',
            'monthlySales',
            'yearlySales',
            'oldPendingOrders',
            'oldPendingCutoffDate',
        ));
    }
}