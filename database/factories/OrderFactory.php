<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'quantity' => $this->faker->numberBetween(1, 10),
            'status' => 'en cours',
            'user_id' => $this->faker->numberBetween(1, 10), // Assurez-vous d'avoir des utilisateurs dans votre DB
            'supplier_id' => $this->faker->numberBetween(1, 10), // Assurez-vous d'avoir des fournisseurs dans votre DB
            'date' => $this->faker->date(),
        ];
    }
}
