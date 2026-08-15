<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreShopkeeperRequest;
use App\Http\Requests\Admin\UpdateShopkeeperRequest;
use App\Models\ActivityLog;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ShopkeeperController extends Controller
{
    public function index(): View
    {
        $shopkeepers = User::where('company_id', auth()->user()->company_id)
            ->where('role', 'shopkeeper')
            ->with('shop')
            ->latest()
            ->paginate(10);

        return view('admin.shopkeepers.index', compact('shopkeepers'));
    }

    public function create(): View
    {
        return view('admin.shopkeepers.create');
    }

    public function store(StoreShopkeeperRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // company_id is filled in automatically by the BelongsToCompany trait.
        $shop = Shop::create([
            'name' => $validated['shop_name'],
            'owner_name' => $validated['owner_name'],
            'address' => $validated['address'],
            'area' => $validated['area'],
            'phone' => $validated['phone'],
            'status' => 'active',
        ]);

        $email = strtolower($validated['email_prefix']) . '@salesconnect.com';
        $generatedPassword = Str::password(10, symbols: false);

        User::create([
            'name' => $validated['owner_name'],
            'email' => $email,
            'password' => Hash::make($generatedPassword),
            'role' => 'shopkeeper',
            'company_id' => auth()->user()->company_id,
            'shop_id' => $shop->id,
            'phone' => $validated['phone'],
            'status' => 'active',
        ]);

        ActivityLog::record(
            'shopkeeper_created',
            "Created shopkeeper account for shop \"{$shop->name}\" ({$email})."
        );

        return redirect()
            ->route('admin.shopkeepers.index')
            ->with('success', 'Shopkeeper created successfully.')
            ->with('generated_email', $email)
            ->with('generated_password', $generatedPassword);
    }

    public function edit(User $shopkeeper): View
    {
        $this->authorizeSameCompany($shopkeeper);

        return view('admin.shopkeepers.edit', compact('shopkeeper'));
    }

    public function update(UpdateShopkeeperRequest $request, User $shopkeeper): RedirectResponse
    {
        $this->authorizeSameCompany($shopkeeper);

        $validated = $request->validated();

        $shopkeeper->shop->update([
            'name' => $validated['shop_name'],
            'owner_name' => $validated['owner_name'],
            'address' => $validated['address'],
            'area' => $validated['area'],
            'phone' => $validated['phone'],
        ]);

        $shopkeeper->update([
            'name' => $validated['owner_name'],
            'email' => strtolower($validated['email_prefix']) . '@salesconnect.com',
            'phone' => $validated['phone'],
        ]);

        return redirect()
            ->route('admin.shopkeepers.index')
            ->with('success', 'Shopkeeper updated successfully.');
    }

    public function destroy(User $shopkeeper): RedirectResponse
    {
        $this->authorizeSameCompany($shopkeeper);

        $shopName = $shopkeeper->shop->name;
        $shopkeeper->shop->delete();
        $shopkeeper->delete();

        ActivityLog::record('shopkeeper_deleted', "Deleted shopkeeper for shop: {$shopName}.");

        return redirect()
            ->route('admin.shopkeepers.index')
            ->with('success', 'Shopkeeper deleted successfully.');
    }

    public function resetPassword(User $shopkeeper): RedirectResponse
    {
        $this->authorizeSameCompany($shopkeeper);

        $newPassword = Str::password(10, symbols: false);

        $shopkeeper->update([
            'password' => Hash::make($newPassword),
        ]);

        return redirect()
            ->route('admin.shopkeepers.index')
            ->with('success', 'Password reset successfully.')
            ->with('generated_email', $shopkeeper->email)
            ->with('generated_password', $newPassword);
    }

    public function toggleStatus(User $shopkeeper): RedirectResponse
    {
        $this->authorizeSameCompany($shopkeeper);

        $newStatus = $shopkeeper->status === 'active' ? 'inactive' : 'active';

        $shopkeeper->update(['status' => $newStatus]);
        $shopkeeper->shop->update(['status' => $newStatus]);

        return redirect()
            ->route('admin.shopkeepers.index')
            ->with('success', 'Status updated successfully.');
    }

    private function authorizeSameCompany(User $shopkeeper): void
    {
        abort_if(
            $shopkeeper->company_id !== auth()->user()->company_id,
            403,
            'You cannot manage a shopkeeper outside your company.'
        );
    }
}