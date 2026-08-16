<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreShopRequest;
use App\Http\Requests\Admin\UpdateShopRequest;
use App\Models\ActivityLog;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(): View
    {
        $shops = Shop::with('shopkeeper')->latest()->paginate(10);

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

        $redirect = redirect()
            ->route('admin.shops.index')
            ->with('success', 'Shop created successfully.');

        // Optional: create a login for this shop's owner right away.
        if (! empty($validated['create_login']) && ! empty($validated['email_prefix'])) {
            $email = strtolower($validated['email_prefix']) . '@salesconnect.com';
            $generatedPassword = Str::password(10, symbols: false);

            User::create([
                'name' => $shop->owner_name,
                'email' => $email,
                'password' => Hash::make($generatedPassword),
                'role' => 'shopkeeper',
                'company_id' => auth()->user()->company_id,
                'shop_id' => $shop->id,
                'phone' => $shop->phone,
                'status' => 'active',
            ]);

            ActivityLog::record('shopkeeper_created', "Created login for shop \"{$shop->name}\" ({$email}).");

            $redirect->with('generated_email', $email)
                     ->with('generated_password', $generatedPassword);
        }

        return $redirect;
    }

    public function edit(Shop $shop): View
    {
        $shop->load('shopkeeper');

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

        // Remove the linked login (if any) before removing the shop.
        $shop->shopkeeper()->delete();
        $shop->delete();

        ActivityLog::record('shop_deleted', "Deleted shop: {$name}.");

        return redirect()
            ->route('admin.shops.index')
            ->with('success', 'Shop deleted successfully.');
    }

    public function toggleStatus(Shop $shop): RedirectResponse
    {
        $newStatus = $shop->status === 'active' ? 'inactive' : 'active';

        $shop->update(['status' => $newStatus]);
        $shop->shopkeeper?->update(['status' => $newStatus]);

        return redirect()
            ->route('admin.shops.index')
            ->with('success', 'Status updated successfully.');
    }

    /**
     * Add a login to a shop that doesn't have one yet.
     */
    public function addLogin(Request $request, Shop $shop): RedirectResponse
    {
        abort_if($shop->shopkeeper, 403, 'This shop already has a login.');

        $validated = $request->validate([
            'email_prefix' => ['required', 'string', 'max:50', 'alpha_dash'],
        ]);

        $email = strtolower($validated['email_prefix']) . '@salesconnect.com';
        $generatedPassword = Str::password(10, symbols: false);

        User::create([
            'name' => $shop->owner_name,
            'email' => $email,
            'password' => Hash::make($generatedPassword),
            'role' => 'shopkeeper',
            'company_id' => auth()->user()->company_id,
            'shop_id' => $shop->id,
            'phone' => $shop->phone,
            'status' => 'active',
        ]);

        ActivityLog::record('shopkeeper_created', "Created login for shop \"{$shop->name}\" ({$email}).");

        return redirect()
            ->route('admin.shops.index')
            ->with('success', 'Login created successfully.')
            ->with('generated_email', $email)
            ->with('generated_password', $generatedPassword);
    }

    public function resetPassword(Shop $shop): RedirectResponse
    {
        abort_if(! $shop->shopkeeper, 404, 'This shop has no login yet.');

        $newPassword = Str::password(10, symbols: false);

        $shop->shopkeeper->update([
            'password' => Hash::make($newPassword),
        ]);

        return redirect()
            ->route('admin.shops.index')
            ->with('success', 'Password reset successfully.')
            ->with('generated_email', $shop->shopkeeper->email)
            ->with('generated_password', $newPassword);
    }
}