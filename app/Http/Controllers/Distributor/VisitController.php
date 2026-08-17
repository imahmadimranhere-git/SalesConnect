<?php

namespace App\Http\Controllers\Distributor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Distributor\StoreVisitRequest;
use App\Models\Shop;
use App\Models\ShopAssignment;
use App\Models\Visit;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VisitController extends Controller
{
    public function routePlan(): View
    {
        $distributorId = auth()->id();
        $today = strtolower(now()->format('l'));

        $assignments = ShopAssignment::with('shop')
            ->where('distributor_id', $distributorId)
            ->where('day_of_week', $today)
            ->orderBy('visit_order')
            ->get();

        $visitedShopIdsToday = Visit::where('distributor_id', $distributorId)
            ->whereDate('visited_at', today())
            ->pluck('shop_id');

        return view('distributor.route-plan', compact('assignments', 'visitedShopIdsToday'));
    }

    public function store(StoreVisitRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $shop = Shop::findOrFail($validated['shop_id']);

        $distance = $shop->distanceInMetersFrom($validated['latitude'], $validated['longitude']);
        $allowedRadius = config('visit.radius_meters');

        if ($distance > $allowedRadius) {
            return back()->with('error', sprintf(
                'You are %.0f meters away from %s. You must be within %d meters to mark this visit.',
                $distance,
                $shop->name,
                $allowedRadius
            ));
        }

        // Prevent marking the same shop twice on the same day.
        $alreadyVisited = Visit::where('distributor_id', auth()->id())
            ->where('shop_id', $shop->id)
            ->whereDate('visited_at', today())
            ->exists();

        if ($alreadyVisited) {
            return back()->with('error', "You have already marked a visit for {$shop->name} today.");
        }

        Visit::create([
            'distributor_id' => auth()->id(),
            'shop_id' => $shop->id,
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'distance_meters' => round($distance, 2),
            'visited_at' => now(),
        ]);

        return back()->with('success', "Visit marked successfully for {$shop->name}.");
    }
}