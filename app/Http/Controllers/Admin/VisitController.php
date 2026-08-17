<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VisitController extends Controller
{
    public function index(Request $request): View
    {
        $visits = Visit::with(['distributor', 'shop'])
            ->when($request->distributor_id, function ($query, $id) {
                $query->where('distributor_id', $id);
            })
            ->when($request->date_from, function ($query, $date) {
                $query->whereDate('visited_at', '>=', $date);
            })
            ->when($request->date_to, function ($query, $date) {
                $query->whereDate('visited_at', '<=', $date);
            })
            ->latest('visited_at')
            ->paginate(15)
            ->withQueryString();

        $distributors = \App\Models\User::where('company_id', auth()->user()->company_id)
            ->where('role', 'distributor')
            ->get(['id', 'name']);

        return view('admin.visits.index', compact('visits', 'distributors'));
    }
}