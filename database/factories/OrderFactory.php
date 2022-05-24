<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
	public function definition(): array
	{
		return [
			'status' => $this->faker->numberBetween(1, 4),
			'payment_type' => $this->faker->numberBetween(1, 3),
			'total_price' => $this->faker->randomFloat(1, 20, 30),
			'reference_number' => $this->faker->randomNumber(3, true),
			'final_price' => $this->faker->randomFloat(1, 30, 50),
			'total_price_minus_discounts' => $this->faker->randomFloat(1, 30, 50),
			'guest_mode' => true,
			'customer_email' => $this->faker->email(),
			'currency' => 'HRK',
			'order_dump' => '[]'
		];
	}
}
