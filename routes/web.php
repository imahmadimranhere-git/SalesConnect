<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\CompanyController;
use App\Http\Controllers\SuperAdmin\ActivityLogController;
use App\Http\Controllers\SuperAdmin\SettingController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DistributorController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ShopAssignmentController;
use App\Http\Controllers\Admin\VisitController;

use App\Http\Controllers\Distributor\DashboardController as DistributorDashboardController;
use App\Http\Controllers\Distributor\VisitController as DistributorVisitController;
use App\Http\Controllers\Distributor\OrderController as DistributorOrderController;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Super Admin Panel Routes

Route::middleware(['auth', 'role:super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('admins', AdminController::class);
    Route::patch('admins/{admin}/reset-password', [AdminController::class, 'resetPassword'])->name('admins.reset-password');
    Route::patch('admins/{admin}/toggle-status', [AdminController::class, 'toggleStatus'])->name('admins.toggle-status');
    
    Route::get('companies', [CompanyController::class, 'index'])->name('companies.index');

    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    
    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    });


// Admin Panel Routes

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('distributors', DistributorController::class);
    Route::patch('distributors/{distributor}/reset-password', [DistributorController::class, 'resetPassword'])->name('distributors.reset-password');
    Route::patch('distributors/{distributor}/toggle-status', [DistributorController::class, 'toggleStatus'])->name('distributors.toggle-status');
    
    Route::resource('shops', ShopController::class)->except(['show']);
    Route::patch('shops/{shop}/toggle-status', [ShopController::class, 'toggleStatus'])->name('shops.toggle-status');
    Route::post('shops/{shop}/add-login', [ShopController::class, 'addLogin'])->name('shops.add-login');
    Route::patch('shops/{shop}/reset-password', [ShopController::class, 'resetPassword'])->name('shops.reset-password');

    Route::resource('products', ProductController::class);
    Route::patch('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');

    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::patch('orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status'); 
    
    Route::get('distributors/{distributor}/assignments', [ShopAssignmentController::class, 'index'])->name('distributors.assignments.index');
    Route::post('distributors/{distributor}/assignments', [ShopAssignmentController::class, 'store'])->name('distributors.assignments.store');
    Route::delete('assignments/{assignment}', [ShopAssignmentController::class, 'destroy'])->name('assignments.destroy');
  
    Route::get('visits', [VisitController::class, 'index'])->name('visits.index');
    
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    });
   

/*
|--------------------------------------------------------------------------
| Distributor Panel Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:distributor'])->prefix('distributor')->name('distributor.')->group(function () {
    Route::get('/dashboard', [DistributorDashboardController::class, 'index'])->name('dashboard');

    Route::get('route-plan', [DistributorVisitController::class, 'routePlan'])->name('route-plan');
    Route::post('visits', [DistributorVisitController::class, 'store'])->name('visits.store');

    Route::resource('orders', DistributorOrderController::class)->only(['index', 'create', 'store', 'show']);
    



    });

/*
|--------------------------------------------------------------------------
| Shopkeeper Panel Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:shopkeeper'])->prefix('shopkeeper')->name('shopkeeper.')->group(function () {
    Route::view('/dashboard', 'shopkeeper.dashboard')->name('dashboard');
});

require __DIR__.'/auth.php';
