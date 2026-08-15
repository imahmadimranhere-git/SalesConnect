<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(): View
    {
        $companies = Company::withCount([
            'users as distributors_count' => function ($query) {
                $query->where('role', 'distributor');
            },
            'users as shopkeepers_count' => function ($query) {
                $query->where('role', 'shopkeeper');
            },
        ])
            ->latest()
            ->paginate(10);

        return view('super-admin.companies.index', compact('companies'));
    }
}