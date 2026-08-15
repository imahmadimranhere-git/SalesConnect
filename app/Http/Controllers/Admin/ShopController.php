<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreShopRequest;
use App\Http\Requests\Admin\UpdateShopRequest;
use App\Models\ActivityLog;
use App\Models\Shop;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(): View
    {
        // BelongsToCompany trait automatically filters this to the
        // logged-in Admin's own company — no manual where() needed.
        $shops = Shop::latest()->paginate(10);

        return view('admin.shops.index', compact('shops'));
    }

    public function create(): View
    {
        return view('admin.shops.create');
    }

    public function store(StoreShopRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $shop = Shop::create([
            'name' => $validated['name'],
            'owner_name' => $validated['owner_name'],
            'address' => $validated['address'],
            'area' => $validated['area'],
            'phone' => $validated['phone'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'status' => 'active',
        ]);

        ActivityLog::record('shop_created', "Created shop: {$shop->name}.");

        return redirect()
            ->route('admin.shops.index')
            ->with('success', 'Shop created successfully.');
    }

    public function edit(Shop $shop): View
    {
        return view('admin.shops.edit', compact('shop'));
    }

    public function update(UpdateShopRequest $request, Shop $shop): RedirectResponse
    {
        $shop->update($request->validated());

        return redirect()
            ->route('admin.shops.index')
            ->with('success', 'Shop updated successfully.');
    }

    public function destroy(Shop $shop): RedirectResponse
    {
        $name = $shop->name;
        $shop->delete();

        ActivityLog::record('shop_deleted', "Deleted shop: {$name}.");

        return redirect()
            ->route('admin.shops.index')
            ->with('success', 'Shop deleted successfully.');
    }

    public function toggleStatus(Shop $shop): RedirectResponse
    {
        $shop->update([
            'status' => $shop->status === 'active' ? 'inactive' : 'active',
        ]);

        return redirect()
            ->route('admin.shops.index')
            ->with('success', 'Status updated successfully.');
    }
}