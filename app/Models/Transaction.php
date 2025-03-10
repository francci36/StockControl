<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'price', // Assurez-vous que cette colonne existe dans la table `transactions`
        'type', // 'entry' ou 'exit'
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

