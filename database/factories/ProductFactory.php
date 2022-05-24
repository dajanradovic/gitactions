<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
	public function definition(): array
	{
		return [
			'name' => $this->faker->name(),
			'slug' => $this->faker->unique()->slug(),
			'active' => true,
			'description' => $this->faker->text(),
			'gratis' => false,
			'price' => $this->faker->randomFloat(1, 20, 30),
			'weight' => $this->faker->randomFloat(1, 20, 30),
			'unit_of_measure' => 2,
			'code' => $this->faker->text(5),
			'sort_number' => 4,
			'unavailable' => false
		];
	}
}
