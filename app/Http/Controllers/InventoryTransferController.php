<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransfer;
use App\Models\Product;
use App\Events\TransferRequested;
use App\Notifications\TransferRequestNotification;
use Illuminate\Http\Request;
use DB;

class InventoryTransferController extends Controller
{
    public function index()
    {
        $transfers = InventoryTransfer::with(['fromStore', 'toStore', 'product'])
            ->when(auth()->user()->store_id, function($query) {
                return $query->where('from_store_id', auth()->user()->store_id)
                    ->orWhere('to_store_id', auth()->user()->store_id);
            })
            ->latest()
            ->paginate(10);

        return view('inventory.transfers.index', compact('transfers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'from_store_id' => 'required|exists:stores,id',
            'to_store_id' => 'required|exists:stores,id|different:from_store_id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($request->product_id);

            // Check if source store has enough stock
            if ($product->stock < $request->quantity) {
                throw new \Exception('Insufficient stock for transfer');
            }

            $transfer = InventoryTransfer::create([
                'from_store_id' => $request->from_store_id,
                'to_store_id' => $request->to_store_id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'status' => 'pending',
                'notes' => $request->notes,
                'requested_by_user_id' => auth()->id()
            ]);

            // Notify receiving store managers
            $toStore = Store::find($request->to_store_id);
            $toStore->users()->role('manager')->each(function($user) use ($transfer) {
                $user->notify(new TransferRequestNotification($transfer));
            });

            DB::commit();
            return redirect()->route('inventory.transfers.index')
                ->with('success', 'Transfer request submitted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('inventory.transfers.index')
                ->with('error', 'Failed to submit transfer request: ' . $e->getMessage());
        }
    }
} 
