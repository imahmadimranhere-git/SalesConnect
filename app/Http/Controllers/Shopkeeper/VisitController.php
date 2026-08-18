<?php

namespace App\Http\Controllers\Shopkeeper;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use Illuminate\View\View;

class VisitController extends Controller
{
    public function index(): View
    {
        $shop = auth()->user()->shop;

        $visits = Visit::with('distributor')
            ->where('shop_id', $shop?->id)
            ->latest('visited_at')
            ->paginate(10);

        return view('shopkeeper.visits.index', compact('visits'));
    }
}