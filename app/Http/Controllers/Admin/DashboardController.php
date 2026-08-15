<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;
use App\Models\Shop;

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

        // Shops, Visits, and Orders cards will be wired up
        // once those modules (and their tables) exist.
        $totalShops = Shop::count();
        $visitedToday = 0;
        $pendingVisits = 0;
        $ordersToday = 0;

        return view('admin.dashboard', compact(
            'totalDistributors',
            'totalShopkeepers',
            'totalShops',
            'visitedToday',
            'pendingVisits',
            'ordersToday',
        ));
    }
}