<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
	public function definition(): array
	{
		return [
			'name' => $this->faker->name(),
			'slug' => $this->faker->unique()->slug(),
			'active' => true,
			'use_parent_filters' => true,
			'description' => $this->faker->text(),
			'adult_only' => false,
			'extra_costs' => 0,
			'group_code' => 'ABC'
		];
	}
}
