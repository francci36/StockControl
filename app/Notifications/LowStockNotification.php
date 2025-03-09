<?php

// app/Notifications/LowStockNotification.php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    use Queueable;

    protected $product;

    public function __construct($product)
    {
        $this->product = $product;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line("Le stock du produit {$this->product->name} est bas.")
                    ->line("Quantité en stock : {$this->product->stock}")
                    ->line('Veuillez réapprovisionner le stock.');
    }

    public function toArray()
    {
        return [
            'product_id' => $this->product->id,
            'stock' => $this->product->stock,
        ];
    }
}
