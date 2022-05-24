<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\RoleRoute;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
	/**
	 * Define the model's default state.
	 */
	public function definition(): array
	{
		return [
			'name' => $this->faker->name(),
			'mode' => Role::LIST_MODE_BLACK,
		];
	}

	public function disallowedMethods(array $methods = []): static
	{
		return $this->state([
			'disallowed_methods' => $methods,
		]);
	}

	public function forbiddenRoute(string $route): static
	{
		return $this->has(RoleRoute::factory(1, ['route' => $route]), 'routes');
	}
}
