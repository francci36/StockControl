<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'name',
        'description',
        'summary', // Assurez-vous que `summary` est inclus
        'created_at',
        'updated_at',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class); // Définissez ici la relation avec Stock
    }
}
