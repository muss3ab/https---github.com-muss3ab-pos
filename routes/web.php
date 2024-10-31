<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InventoryTransferController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('products', ProductController::class);
    // Route::resource('sales', SaleController::class);
    // Route::get('/pos', [SaleController::class, 'createPOS'])->name('pos');
    Route::resource('customers', CustomerController::class);
    Route::resource('suppliers', SupplierController::class);

    // User management routes
    Route::group(['middleware' => ['permission:manage users']], function () {
        // Route::resource('users', UserController::class);
        // Route::post('users/{user}/roles', [UserController::class, 'updateRoles'])->name('users.roles.update');
    });

    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::resource('transfers', InventoryTransferController::class);
        Route::post('transfers/{transfer}/approve', [InventoryTransferController::class, 'approve'])
            ->name('transfers.approve');
    });
});

Route::get('/api/products/{product}', [ProductController::class, 'show']);

Route::prefix('reports')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/sales', [ReportController::class, 'salesReport'])->name('reports.sales');
    Route::get('/inventory', [ReportController::class, 'inventoryReport'])->name('reports.inventory');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Users Management
    Route::resource('users', UserController::class);

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
        Route::get('/users', [ReportController::class, 'users'])->name('users');
        Route::get('/export/{type}', [ReportController::class, 'export'])->name('export');
    });
});

require __DIR__.'/auth.php';
