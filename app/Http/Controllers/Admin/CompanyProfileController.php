<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CompanyProfileController extends Controller
{
    public function edit(): View
    {
        $company = auth()->user()->company;

        return view('admin.company-profile.edit', compact('company'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        $company = auth()->user()->company;

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('company-logos', 'public');
        } else {
            unset($validated['logo']);
        }

        $company->update($validated);

        ActivityLog::record('company_profile_updated', "Updated company profile: {$company->name}.");

        return redirect()
            ->route('admin.company-profile.edit')
            ->with('success', 'Company profile updated successfully.');
    }
}