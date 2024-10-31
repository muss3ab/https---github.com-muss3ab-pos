<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController as ApiProductController;
use App\Http\Controllers\Api\SaleController as ApiSaleController;
use App\Http\Controllers\Api\DashboardController as ApiDashboardController;
use App\Http\Controllers\Api\ReportController as ApiReportController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\StoreSaleController;
use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_plugin')
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });

// Route::get('/products/barcode/{barcode}', [ProductController::class, 'findByBarcode']);

Route::prefix('v1')->group(function () {
    // Authentication
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    Route::middleware('auth:sanctum')->group(function () {
        // Store
        Route::get('/stores', [StoreController::class, 'index']);
        Route::get('/stores/{store}', [StoreController::class, 'show']);

        // Products
        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/products/{product}', [ProductController::class, 'show']);
        Route::post('/products/search', [ProductController::class, 'search']);

        // Sales
        Route::post('/sales', [StoreSaleController::class, 'store']);
        Route::get('/sales', [StoreSaleController::class, 'index']);
        Route::get('/sales/{sale}', [StoreSaleController::class, 'show']);

        // Analytics
        Route::get('/analytics/dashboard', [AnalyticsController::class, 'dashboard']);
        Route::get('/analytics/sales', [AnalyticsController::class, 'sales']);
        Route::get('/analytics/inventory', [AnalyticsController::class, 'inventory']);

        // User
        Route::get('/user', [UserController::class, 'show']);
        Route::post('/user/update', [UserController::class, 'update']);
    });
});
