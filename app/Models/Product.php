<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Product extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'supplier_id',
        'stock_threshold',
        'stock',
    ];
    /**
     * Relation avec le fournisseur.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Relation avec les commandes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */ 
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relation avec le stock.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    /**
     * Relation avec les transactions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
