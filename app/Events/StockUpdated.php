<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StockUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $product;
    public $store_id;

    public function __construct($product, $store_id)
    {
        $this->product = $product;
        $this->store_id = $store_id;
    }

    public function broadcastOn()
    {
        return new Channel('store.'.$this->store_id);
    }
}
