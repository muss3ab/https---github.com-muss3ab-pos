<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\Product;
use Carbon\Carbon;

class ReportingService
{
    public function getSalesAnalytics($startDate, $endDate)
    {
        return [
            'total_sales' => $this->getTotalSales($startDate, $endDate),
            'sales_by_category' => $this->getSalesByCategory($startDate, $endDate),
            'sales_by_hour' => $this->getSalesByHour($startDate, $endDate),
            'payment_methods' => $this->getPaymentMethodStats($startDate, $endDate),
            'top_products' => $this->getTopProducts($startDate, $endDate),
            'sales_growth' => $this->calculateSalesGrowth($startDate, $endDate)
        ];
    }

    private function getTotalSales($startDate, $endDate)
    {
        return Sale::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                COUNT(*) as total_transactions,
                SUM(total_amount) as total_amount,
                AVG(total_amount) as average_sale
            ')
            ->first();
    }

    private function getSalesByCategory($startDate, $endDate)
    {
        return Sale::join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->whereBetween('sales.created_at', [$startDate, $endDate])
            ->groupBy('products.category')
            ->selectRaw('
                products.category,
                SUM(sale_items.quantity) as total_items,
                SUM(sale_items.subtotal) as total_amount
            ')
            ->get();
    }

    private function getSalesByHour($startDate, $endDate)
    {
        return Sale::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('hour')
            ->get();
    }

    private function calculateSalesGrowth($startDate, $endDate)
    {
        $previousPeriodStart = Carbon::parse($startDate)->subDays(Carbon::parse($startDate)->diffInDays($endDate));

        $currentPeriodSales = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');

        $previousPeriodSales = Sale::whereBetween('created_at', [$previousPeriodStart, $startDate])
            ->sum('total_amount');

        return [
            'current_period' => $currentPeriodSales,
            'previous_period' => $previousPeriodSales,
            'growth_percentage' => $previousPeriodSales > 0
                ? (($currentPeriodSales - $previousPeriodSales) / $previousPeriodSales) * 100
                : 0
        ];
    }
}
