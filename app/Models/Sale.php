<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    // Définir les colonnes mass assignables
    protected $fillable = [
        'product_id',
        'quantity',
        'total_price',
        'payment_mode',
    ];

    /**
     * Relation avec le modèle Product.
     * Chaque vente est associée à un produit.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relation avec le modèle User (facultative).
     * Chaque vente pourrait être associée à un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
