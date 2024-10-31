<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreSaleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,card',
            'paid_amount' => 'required|numeric'
        ]);

        try {
            DB::beginTransaction();

            $sale = Sale::create([
                'store_id' => auth()->user()->store_id,
                'user_id' => auth()->id(),
                'invoice_number' => 'INV-' . time(),
                'payment_method' => $request->payment_method,
                'paid_amount' => $request->paid_amount
            ]);

            $total = 0;

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}");
                }

                $subtotal = $product->price * $item['quantity'];
                $total += $subtotal;

                $sale->saleItems()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            $sale->update([
                'total_amount' => $total,
                'change_amount' => $request->paid_amount - $total
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'sale' => $sale->load('saleItems.product')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
