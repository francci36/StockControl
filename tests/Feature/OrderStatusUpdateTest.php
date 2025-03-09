<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderStatusUpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_increments_product_stock_when_order_status_changes_to_arrived()
    {
        $product = Product::factory()->create(['stock' => 10]);
        $order = Order::factory()->create([
            'product_id' => $product->id,
            'quantity' => 5,
            'status' => 'en cours'
        ]);

        $response = $this->patch(route('orders.updateStatus', $order->id), [
            'status' => 'arrivé'
        ]);

        $response->assertRedirect(route('orders.index'));
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'arrivé'
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 15
        ]);
    }

    /** @test */
    public function it_decrements_product_stock_when_order_status_changes_from_arrived_to_other_status()
    {
        $product = Product::factory()->create(['stock' => 15]);
        $order = Order::factory()->create([
            'product_id' => $product->id,
            'quantity' => 5,
            'status' => 'arrivé'
        ]);

        $response = $this->patch(route('orders.updateStatus', $order->id), [
            'status' => 'en cours'
        ]);

        $response->assertRedirect(route('orders.index'));
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'en cours'
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 10
        ]);
    }
}
