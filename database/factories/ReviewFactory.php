<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
	public function definition(): array
	{
		return [
		];
	}

	public function general(): static
	{
		return $this->state([
			'description' => $this->faker->text(100),

		]);
	}

	public function product(): static
	{
		return $this->state([
			'rating' => $this->faker->numberBetween(1, 5)

		]);
	}
}
