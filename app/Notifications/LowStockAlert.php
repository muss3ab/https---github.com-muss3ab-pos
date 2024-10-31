<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LowStockAlert extends Notification
{
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject('Low Stock Alert')
            ->line('The following products are running low on stock:');

        foreach ($this->products as $product) {
            $message->line("- {$product->name}: {$product->stock} remaining (Alert level: {$product->alert_stock})");
        }

        return $message->action('View Inventory', url('/products'));
    }
} 
