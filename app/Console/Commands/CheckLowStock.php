<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\User;
use App\Notifications\LowStockAlert;
use Illuminate\Console\Command;

class CheckLowStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for low stock items and send notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lowStockProducts = Product::whereRaw('stock <= alert_stock')->get();

        if ($lowStockProducts->count() > 0) {
            // Notify all admin users
            User::role('admin')->each(function ($user) use ($lowStockProducts) {
                $user->notify(new LowStockAlert($lowStockProducts));
            });
        }
    }
}
