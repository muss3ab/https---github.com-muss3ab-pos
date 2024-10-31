<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\Product;
use Carbon\Carbon;

class AnalyticsService
{
    public function getStoreMetrics($store_id, $startDate, $endDate)
    {
        return [
            'sales' => $this->getSalesMetrics($store_id, $startDate, $endDate),
            'inventory' => $this->getInventoryMetrics($store_id),
            'performance' => $this->getPerformanceMetrics($store_id, $startDate, $endDate),
            'predictions' => $this->getPredictions($store_id)
        ];
    }

    private function getSalesMetrics($store_id, $startDate, $endDate)
    {
        return Sale::where('store_id', $store_id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                COUNT(*) as total_sales,
                SUM(total_amount) as revenue,
                AVG(total_amount) as average_sale,
                COUNT(DISTINCT DATE(created_at)) as active_days,
                SUM(total_amount) / COUNT(DISTINCT DATE(created_at)) as daily_average
            ')
            ->first();
    }

    private function getInventoryMetrics($store_id)
    {
        return Product::where('store_id', $store_id)
            ->selectRaw('
                COUNT(*) as total_products,
                SUM(stock * price) as inventory_value,
                COUNT(CASE WHEN stock <= alert_stock THEN 1 END) as low_stock_items
            ')
            ->first();
    }

    private function getPredictions($store_id)
    {
        // Implement basic sales prediction algorithm
        $historicalSales = Sale::where('store_id', $store_id)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->get();

        // Calculate moving average
        $movingAverage = $historicalSales->avg('total');

        return [
            'predicted_daily_sales' => $movingAverage,
            'predicted_monthly_sales' => $movingAverage * 30,
            'confidence_score' => 0.85 // Example confidence score
        ];
    }
} 
