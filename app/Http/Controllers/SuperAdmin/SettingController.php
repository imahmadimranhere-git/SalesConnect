<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\UpdateSettingRequest;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        $setting = Setting::current();

        return view('super-admin.settings.edit', compact('setting'));
    }

    public function update(UpdateSettingRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $setting = Setting::current();

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        } else {
            unset($validated['logo']);
        }

        $setting->update($validated);

        ActivityLog::record('settings_updated', 'Updated system settings.');

        return redirect()
            ->route('super-admin.settings.edit')
            ->with('success', 'Settings updated successfully.');
    }
}