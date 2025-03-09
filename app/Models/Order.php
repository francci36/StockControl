<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Order extends Model
{
    protected $fillable = ['user_id', 'supplier_id', 'status', 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    

    public function updateStock($increment = true)
{
    Log::info('Début de la mise à jour des stocks', ['order_id' => $this->id, 'increment' => $increment]);
    
    foreach ($this->items as $item) {
        $product = $item->product;
        $quantity = $item->quantity;
        
        Log::info('Produit avant mise à jour', ['product_id' => $product->id, 'stock' => $product->stock, 'quantity' => $quantity]);
        
        if ($increment) {
            $product->stock += $quantity;
        } else {
            $product->stock -= $quantity;
        }

        Log::info('Produit après mise à jour', ['product_id' => $product->id, 'stock' => $product->stock]);

        $product->save();
    }
}





    protected function date(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value),
        );
    }
}

