<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
	public function definition(): array
	{
		return [
			'order_id' => Order::factory(),
			'product_id' => Product::factory(),
			'price' => $this->faker->randomFloat(1, 30, 50),
			'order_item_details' => '[]',
			'total_price' => $this->faker->randomFloat(1, 30, 50),
			'total_price_minus_discounts' => $this->faker->randomFloat(1, 30, 50),
			'quantity' => $this->faker->randomDigit()
		];
	}
}
