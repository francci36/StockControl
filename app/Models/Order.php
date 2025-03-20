<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Order extends Model
{
    /**
     * Les colonnes pouvant être remplies massivement.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'supplier_id',
        'status',
        'date',
        'total_amount',
    ];

    /**
     * Les colonnes à caster automatiquement.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date', // Convertit la colonne `date` en instance Carbon
    ];

    /**
     * Relation avec les produits via la table pivot `order_items`.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_items')
                    ->withPivot('quantity', 'price') // Inclut les colonnes supplémentaires de la table pivot
                    ->withTimestamps(); // Ajoute les timestamps de la table pivot
    }

    /**
     * Relation avec l'utilisateur.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le fournisseur.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Relation avec les items de commande.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Accesseur pour formater la date.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function formattedDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => Carbon::parse($attributes['date'])->format('d/m/Y'),
        );
    }

    /**
     * Accesseur pour vérifier si la commande est en attente.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function isPending(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['status'] === 'pending',
        );
    }

    /**
     * Accesseur pour vérifier si la commande est terminée.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function isCompleted(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['status'] === 'completed',
        );
    }

    /**
     * Méthode pour recalculer `total_amount` depuis `orderItems`.
     *
     * @return float
     */
    public function calculateTotalAmount(): float
    {
        return $this->orderItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }
}
