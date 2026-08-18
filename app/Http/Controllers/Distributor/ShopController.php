<?php

namespace App\Http\Controllers\Distributor;

use App\Http\Controllers\Controller;
use App\Models\ShopAssignment;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(): View
    {
        $assignments = ShopAssignment::with('shop')
            ->where('distributor_id', auth()->id())
            ->get()
            ->groupBy('shop_id');

        return view('distributor.shops.index', compact('assignments'));
    }
}