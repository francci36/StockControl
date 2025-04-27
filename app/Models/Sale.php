<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'total_price',
        'payment_mode',
        'user_id',
        'status', // Nouveau champ
        'payment_reference' // Nouveau champ pour référence de paiement
    ];

    // Relation avec les produits (many-to-many)
    public function products()
    {
        return $this->belongsToMany(Product::class, 'sale_product') // Utilisez 'sale_product'
            ->withPivot('quantity', 'unit_price', 'total_price')
            ->withTimestamps();
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Nouvelle relation avec les transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}