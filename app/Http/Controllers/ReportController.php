<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function salesReport(Request $request)
    {
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        $sales = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->with('saleItems.product')
            ->get();

        $summary = [
            'total_sales' => $sales->sum('total_amount'),
            'total_transactions' => $sales->count(),
            'average_sale' => $sales->avg('total_amount'),
            'payment_methods' => $sales->groupBy('payment_method')
                ->map(function ($group) {
                    return [
                        'count' => $group->count(),
                        'total' => $group->sum('total_amount')
                    ];
                })
        ];

        return view('reports.sales', compact('sales', 'summary', 'startDate', 'endDate'));
    }

    public function inventoryReport()
    {
        $products = Product::withCount(['saleItems as total_sold' => function($query) {
            $query->whereMonth('created_at', Carbon::now()->month);
        }])
        ->get()
        ->map(function ($product) {
            $product->revenue = $product->total_sold * $product->price;
            return $product;
        });

        return view('reports.inventory', compact('products'));
    }
}
