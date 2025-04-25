<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    // Active les colonnes timestamps comme updated_at et created_at
    public $timestamps = true;

    // Définissez les colonnes modifiables
    protected $fillable = [
        'product_id',
        'quantity',
        'report_id',
    ];

    // Relation avec le produit
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
