<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TransferRequestNotification extends Notification
{
    protected $transfer;

    public function __construct($transfer)
    {
        $this->transfer = $transfer;
    }

    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Inventory Transfer Request')
            ->line('A new inventory transfer request has been created.')
            ->line("From: {$this->transfer->fromStore->name}")
            ->line("Product: {$this->transfer->product->name}")
            ->line("Quantity: {$this->transfer->quantity}")
            ->action('View Transfer', url('/inventory/transfers/'.$this->transfer->id));
    }

    public function toDatabase($notifiable)
    {
        return [
            'transfer_id' => $this->transfer->id,
            'message' => "New transfer request from {$this->transfer->fromStore->name}"
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'transfer_id' => $this->transfer->id,
            'message' => "New transfer request from {$this->transfer->fromStore->name}"
        ]);
    }
}
