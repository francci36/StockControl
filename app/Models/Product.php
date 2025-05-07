<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'supplier_id',
        'stock_threshold' => 5,
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function stock()
    {
        return $this->hasOne(Stock::class, 'product_id', 'id');
    }


    public function getStockThresholdAttribute($value)
    {
        return $value ?? 5; // Utilise 5 comme valeur par défaut
    }

    public function sales()
    {
        return $this->belongsToMany(Sale::class, 'sale_product') // Utilisez 'sale_product'
            ->withPivot('quantity', 'unit_price', 'total_price')
            ->withTimestamps();
    }



}