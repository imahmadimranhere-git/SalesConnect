<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreShopAssignmentRequest;
use App\Models\ActivityLog;
use App\Models\Shop;
use App\Models\ShopAssignment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ShopAssignmentController extends Controller
{
    private array $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

    public function index(User $distributor): View
    {
        abort_if($distributor->company_id !== auth()->user()->company_id || $distributor->role !== 'distributor', 404);

        $assignments = ShopAssignment::with('shop')
            ->where('distributor_id', $distributor->id)
            ->orderBy('day_of_week')
            ->orderBy('visit_order')
            ->get()
            ->groupBy('day_of_week');

        $shops = Shop::orderBy('name')->get(['id', 'name', 'area']);

        // Work out the next available visit order for each day (max existing + 1).
        $nextOrderByDay = [];
        foreach ($this->days as $day) {
            $nextOrderByDay[$day] = $assignments->get($day, collect())->max('visit_order') + 1;
        }

        return view('admin.assignments.index', [
            'distributor' => $distributor,
            'assignments' => $assignments,
            'shops' => $shops,
            'days' => $this->days,
            'nextOrderByDay' => $nextOrderByDay,
        ]);
    }

    public function store(StoreShopAssignmentRequest $request, User $distributor): RedirectResponse
    {
        abort_if($distributor->company_id !== auth()->user()->company_id || $distributor->role !== 'distributor', 404);

        $validated = $request->validated();

        $alreadyExists = ShopAssignment::where('distributor_id', $distributor->id)
            ->where('shop_id', $validated['shop_id'])
            ->where('day_of_week', $validated['day_of_week'])
            ->exists();

        if ($alreadyExists) {
            return back()->with('error', 'This shop is already assigned to this distributor on this day.');
        }

        ShopAssignment::create([
            'distributor_id' => $distributor->id,
            'shop_id' => $validated['shop_id'],
            'day_of_week' => $validated['day_of_week'],
            'visit_order' => $validated['visit_order'],
        ]);

        ActivityLog::record('shop_assigned', "Assigned a shop to distributor {$distributor->name} on {$validated['day_of_week']}.");

        return redirect()
            ->route('admin.distributors.assignments.index', $distributor)
            ->with('success', 'Shop assigned successfully.');
    }

    public function destroy(ShopAssignment $assignment): RedirectResponse
    {
        $distributor = $assignment->distributor;
        $assignment->delete();

        return redirect()
            ->route('admin.distributors.assignments.index', $distributor)
            ->with('success', 'Assignment removed successfully.');
    }
}