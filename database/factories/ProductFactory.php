<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'unit' => 'kg',
            'price' => $this->faker->randomFloat(2, 1, 100),
            'stock' => $this->faker->numberBetween(0, 100),
            'supplier_id' => $this->faker->numberBetween(1, 10), // Assurez-vous d'avoir des fournisseurs dans votre DB
        ];
    }
}
