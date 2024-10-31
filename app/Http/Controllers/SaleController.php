<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function createPOS()
    {
        $products = Product::where('stock', '>', 0)->get();
        $categories = Product::distinct('category')->pluck('category');

        return view('pos.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $sale = Sale::create([
                'invoice_number' => 'INV-' . time(),
                'total_amount' => $request->total_amount,
                'paid_amount' => $request->paid_amount,
                'change_amount' => $request->change_amount,
                'payment_method' => $request->payment_method
            ]);

            foreach ($request->items as $item) {
                $sale->saleItems()->create([
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity']
                ]);

                // Update product stock
                $product = Product::find($item['id']);
                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();
            return response()->json(['success' => true, 'sale_id' => $sale->id]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
