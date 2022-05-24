<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Discount>
 */
class DiscountFactory extends Factory
{
	public function definition(): array
	{
		return [
			'title' => $this->faker->text(20),
			'max_use' => 0,
			'period_from' => formatTimestamp(Carbon::now()->addDays(2)),
			'period_to' => formatTimestamp(Carbon::now()->addDays(5)),
			'code' => $this->faker->text(10),
			'amount' => $this->faker->randomNumber(2, true),
			'is_percentage' => $this->faker->boolean(),
			'active' => $this->faker->boolean(),
			'type' => $this->faker->numberBetween(1, 3)
		];
	}
}
