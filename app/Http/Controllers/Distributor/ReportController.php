<?php

namespace App\Http\Controllers\Distributor;

use App\Http\Controllers\Controller;
use App\Models\Order;
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

        $baseQuery = Order::where('distributor_id', auth()->id())
            ->where('status', 'delivered')
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to);

        $totalSales = (clone $baseQuery)->sum('total_amount');
        $totalOrders = (clone $baseQuery)->count();

        return view('distributor.reports.sales', compact('totalSales', 'totalOrders', 'from', 'to'));
    }

    public function visits(Request $request): View
    {
        [$from, $to] = $this->dateRange($request);

        $totalVisits = Visit::where('distributor_id', auth()->id())
            ->whereDate('visited_at', '>=', $from)
            ->whereDate('visited_at', '<=', $to)
            ->count();

        return view('distributor.reports.visits', compact('totalVisits', 'from', 'to'));
    }

    public function orders(Request $request): View
    {
        [$from, $to] = $this->dateRange($request);

        $baseQuery = Order::where('distributor_id', auth()->id())
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to);

        $pending = (clone $baseQuery)->where('status', 'pending')->count();
        $delivered = (clone $baseQuery)->where('status', 'delivered')->count();
        $cancelled = (clone $baseQuery)->where('status', 'cancelled')->count();

        $orders = (clone $baseQuery)->with('shop')
            ->orderBy('id', 'asc')
            ->paginate(15)
            ->withQueryString();

        return view('distributor.reports.orders', compact('pending', 'delivered', 'cancelled', 'orders', 'from', 'to'));
    }
}