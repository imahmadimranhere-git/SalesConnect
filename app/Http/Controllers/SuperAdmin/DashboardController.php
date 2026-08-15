<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalCompanies = Company::count();
        $totalDistributors = User::where('role', 'distributor')->count();
        $totalShopkeepers = User::where('role', 'shopkeeper')->count();

        // "Total Shops" will come from the shops table once it exists.
        // For now, shopkeepers count acts as a stand-in since every
        // shopkeeper is linked to exactly one shop.
        $totalShops = $totalShopkeepers;

        return view('super-admin.dashboard', compact(
            'totalCompanies',
            'totalDistributors',
            'totalShopkeepers',
            'totalShops',
        ));
    }
}