<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\StoreAdminRequest;
use App\Http\Requests\SuperAdmin\UpdateAdminRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\ActivityLog;

class AdminController extends Controller
{
    public function index(): View
    {
        $admins = User::where('role', 'admin')
            ->with('company')
            ->latest()
            ->paginate(10);

        return view('super-admin.admins.index', compact('admins'));
    }

    public function create(): View
    {
        return view('super-admin.admins.create');
    }

    public function store(StoreAdminRequest $request): RedirectResponse
{
    $validated = $request->validated();

    $company = Company::create([
        'name' => $validated['company_name'],
        'status' => 'active',
    ]);

    $email = strtolower($validated['email_prefix']) . '@salesconnect.com';
    $generatedPassword = Str::password(10, symbols: false);

    User::create([
        'name' => $validated['owner_name'],
        'email' => $email,
        'password' => Hash::make($generatedPassword),
        'role' => 'admin',
        'company_id' => $company->id,
        'phone' => $validated['phone'],
        'status' => 'active',
    ]);

    ActivityLog::record(
        'admin_created',
        "Created admin account for company \"{$company->name}\" ({$email})."
    );

    return redirect()
        ->route('super-admin.admins.index')
        ->with('success', 'Admin created successfully.')
        ->with('generated_email', $email)
        ->with('generated_password', $generatedPassword);
}

    public function edit(User $admin): View
    {
        return view('super-admin.admins.edit', compact('admin'));
    }

    public function update(UpdateAdminRequest $request, User $admin): RedirectResponse
    {
        $validated = $request->validated();

        $admin->company->update([
            'name' => $validated['company_name'],
        ]);

        $admin->update([
            'name' => $validated['owner_name'],
            'email' => strtolower($validated['email_prefix']) . '@salesconnect.com',
            'phone' => $validated['phone'],
        ]);

        return redirect()
            ->route('super-admin.admins.index')
            ->with('success', 'Admin updated successfully.');
    }

    public function destroy(User $admin): RedirectResponse
{
    $companyName = $admin->company->name;
    $email = $admin->email;

    $admin->delete();

    ActivityLog::record(
        'admin_deleted',
        "Deleted admin account for company \"{$companyName}\" ({$email})."
    );

    return redirect()
        ->route('super-admin.admins.index')
        ->with('success', 'Admin deleted successfully.');
}

    public function resetPassword(User $admin): RedirectResponse
    {
        $newPassword = Str::password(10, symbols: false);

        $admin->update([
            'password' => Hash::make($newPassword),
        ]);

        return redirect()
            ->route('super-admin.admins.index')
            ->with('success', 'Password reset successfully.')
            ->with('generated_email', $admin->email)
            ->with('generated_password', $newPassword);
    }

   public function toggleStatus(User $admin): RedirectResponse
{
    $newStatus = $admin->status === 'active' ? 'inactive' : 'active';

    $admin->update(['status' => $newStatus]);

    // Suspending the Admin suspends their entire company —
    // this automatically blocks that company's Distributors and Shopkeepers too.
    $admin->company?->update(['status' => $newStatus]);

    return redirect()
        ->route('super-admin.admins.index')
        ->with('success', 'Status updated successfully.');
}
}