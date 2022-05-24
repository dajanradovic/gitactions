<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\Admin;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
	/**
	 * Define the model's default state.
	 */
	public function definition(): array
	{
		return [
			'name' => $this->faker->name(),
			'email' => $this->faker->unique()->safeEmail(),
			'password' => 'test1234',
			'user_id' => Admin::factory(),
			'user_type' => Admin::class,
		];
	}

	public function asCustomer(): static
	{
		return $this->state([
			'user_id' => Customer::factory(),
			'user_type' => Customer::class,
		]);
	}

	public function inactive(): static
	{
		return $this->state([
			'active' => false,
		]);
	}

	public function withDisallowedMethodsRole(array $methods = []): static
	{
		return $this->state([
			'role_id' => Role::factory()->disallowedMethods($methods),
		]);
	}

	public function withForbiddenRouteRole(string $route): static
	{
		return $this->state([
			'role_id' => Role::factory()->forbiddenRoute($route),
		]);
	}
}
