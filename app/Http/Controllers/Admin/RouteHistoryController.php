<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RouteHistoryController extends Controller
{
    public function index(Request $request): View
    {
        $distributors = User::where('company_id', auth()->user()->company_id)
            ->where('role', 'distributor')
            ->get(['id', 'name']);

        $visits = collect();
        $selectedDistributor = null;
        $selectedDate = $request->date ?: today()->toDateString();

        if ($request->distributor_id) {
            $selectedDistributor = User::find($request->distributor_id);

            $visits = Visit::with('shop')
                ->where('distributor_id', $request->distributor_id)
                ->whereDate('visited_at', $selectedDate)
                ->orderBy('visited_at')
                ->get();
        }

        return view('admin.route-history.index', compact(
            'distributors',
            'visits',
            'selectedDistributor',
            'selectedDate',
        ));
    }
}