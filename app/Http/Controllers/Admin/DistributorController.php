<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDistributorRequest;
use App\Http\Requests\Admin\UpdateDistributorRequest;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\Request;


class DistributorController extends Controller
{
    public function index(Request $request): View
{
    $distributors = User::where('company_id', auth()->user()->company_id)
        ->where('role', 'distributor')
        ->when($request->search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('admin.distributors.index', compact('distributors'));
}

    public function create(): View
    {
        return view('admin.distributors.create');
    }

    public function store(StoreDistributorRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $email = strtolower($validated['email_prefix']) . '@salesconnect.com';
        $generatedPassword = Str::password(10, symbols: false);

        User::create([
            'name' => $validated['name'],
            'email' => $email,
            'password' => Hash::make($generatedPassword),
            'role' => 'distributor',
            'company_id' => auth()->user()->company_id,
            'phone' => $validated['phone'],
            'status' => 'active',
        ]);

        ActivityLog::record(
            'distributor_created',
            "Created distributor account: {$validated['name']} ({$email})."
        );

        return redirect()
            ->route('admin.distributors.index')
            ->with('success', 'Distributor created successfully.')
            ->with('generated_email', $email)
            ->with('generated_password', $generatedPassword);
    }

    public function edit(User $distributor): View
    {
        $this->authorizeSameCompany($distributor);

        return view('admin.distributors.edit', compact('distributor'));
    }

    public function update(UpdateDistributorRequest $request, User $distributor): RedirectResponse
    {
        $this->authorizeSameCompany($distributor);

        $validated = $request->validated();

        $distributor->update([
            'name' => $validated['name'],
            'email' => strtolower($validated['email_prefix']) . '@salesconnect.com',
            'phone' => $validated['phone'],
        ]);

        return redirect()
            ->route('admin.distributors.index')
            ->with('success', 'Distributor updated successfully.');
    }

    public function destroy(User $distributor): RedirectResponse
    {
        $this->authorizeSameCompany($distributor);

        $name = $distributor->name;
        $distributor->delete();

        ActivityLog::record('distributor_deleted', "Deleted distributor: {$name}.");

        return redirect()
            ->route('admin.distributors.index')
            ->with('success', 'Distributor deleted successfully.');
    }

    public function resetPassword(User $distributor): RedirectResponse
    {
        $this->authorizeSameCompany($distributor);

        $newPassword = Str::password(10, symbols: false);

        $distributor->update([
            'password' => Hash::make($newPassword),
        ]);

        return redirect()
            ->route('admin.distributors.index')
            ->with('success', 'Password reset successfully.')
            ->with('generated_email', $distributor->email)
            ->with('generated_password', $newPassword);
    }

    public function toggleStatus(User $distributor): RedirectResponse
    {
        $this->authorizeSameCompany($distributor);

        $distributor->update([
            'status' => $distributor->status === 'active' ? 'inactive' : 'active',
        ]);

        return redirect()
            ->route('admin.distributors.index')
            ->with('success', 'Status updated successfully.');
    }

    /**
     * Guard against an Admin accessing/editing a distributor
     * that belongs to a DIFFERENT company (via direct URL tampering).
     */
    private function authorizeSameCompany(User $distributor): void
    {
        abort_if(
            $distributor->company_id !== auth()->user()->company_id,
            403,
            'You cannot manage a distributor outside your company.'
        );
    }
}