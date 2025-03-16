<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'product_id', 'quantity', 'price'];

    // Relation avec une commande
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relation avec un produit
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accessoire pour le prix total (quantité x prix unitaire)
    public function getTotalPriceAttribute()
    {
        return $this->quantity * $this->price;
    }
}
