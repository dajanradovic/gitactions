<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
	public function definition(): array
	{
		return [
			'name' => $this->faker->name(),
			'measure' => $this->faker->randomNumber(1,10),
			'price' => $this->faker->randomFloat(1, 20, 30),
			'weight' => $this->faker->randomFloat(1, 20, 30),
			'product_id' => Product::factory()
		];
	}
}
