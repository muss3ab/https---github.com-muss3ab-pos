<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use App\Models\Sale;
use App\Models\Product;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function index()
    {
        $store_id = auth()->user()->store_id;
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now();

        $metrics = $this->analyticsService->getStoreMetrics(
            $store_id,
            $startDate,
            $endDate
        );

        $lowStockProducts = Product::where('store_id', $store_id)
            ->whereRaw('stock <= alert_stock')
            ->get();

        $recentSales = Sale::where('store_id', $store_id)
            ->with(['user', 'saleItems.product'])
            ->latest()
            ->take(5)
            ->get();

        $salesChart = $this->analyticsService->getSalesChartData(
            $store_id,
            $startDate,
            $endDate
        );

        return view('dashboard', compact(
            'metrics',
            'lowStockProducts',
            'recentSales',
            'salesChart'
        ));
    }
}
