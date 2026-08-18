<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    private function dateRange(Request $request): array
    {
        return [
            $request->date_from ?: now()->startOfMonth()->toDateString(),
            $request->date_to ?: now()->toDateString(),
        ];
    }

    public function sales(Request $request): View
    {
        [$from, $to] = $this->dateRange($request);

        $baseQuery = Order::where('status', 'delivered')
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to);

        $totalSales = (clone $baseQuery)->sum('total_amount');
        $totalOrders = (clone $baseQuery)->count();

        $byDistributor = (clone $baseQuery)
            ->with('distributor')
            ->get()
            ->groupBy('distributor_id')
            ->map(function ($orders) {
                return [
                    'name' => $orders->first()->distributor?->name ?? 'N/A',
                    'orders' => $orders->count(),
                    'total' => $orders->sum('total_amount'),
                ];
            });

        return view('admin.reports.sales', compact('totalSales', 'totalOrders', 'byDistributor', 'from', 'to'));
    }

    public function visits(Request $request): View
    {
        [$from, $to] = $this->dateRange($request);

        $baseQuery = Visit::whereDate('visited_at', '>=', $from)
            ->whereDate('visited_at', '<=', $to);

        $totalVisits = (clone $baseQuery)->count();

        $byDistributor = (clone $baseQuery)
            ->with('distributor')
            ->get()
            ->groupBy('distributor_id')
            ->map(function ($visits) {
                return [
                    'name' => $visits->first()->distributor?->name ?? 'N/A',
                    'visits' => $visits->count(),
                ];
            });

        return view('admin.reports.visits', compact('totalVisits', 'byDistributor', 'from', 'to'));
    }

    public function orders(Request $request): View
    {
        [$from, $to] = $this->dateRange($request);

        $baseQuery = Order::whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to);

        $pending = (clone $baseQuery)->where('status', 'pending')->count();
        $delivered = (clone $baseQuery)->where('status', 'delivered')->count();
        $cancelled = (clone $baseQuery)->where('status', 'cancelled')->count();

        $orders = (clone $baseQuery)->with(['shop', 'distributor'])
            ->orderBy('id', 'asc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.reports.orders', compact('pending', 'delivered', 'cancelled', 'orders', 'from', 'to'));
    }
}