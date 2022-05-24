<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin>
 */
class CustomerFactory extends Factory
{
	/**
	 * Define the model's default state.
	 */
	public function definition(): array
	{
		return [
			'surname' => $this->faker->name(),
			'oib' => $this->faker->numerify('###########'),
			'date_of_birth' => $this->faker->date('Y-m-d'),
			'company_name' => 'Llojc Eternal',
			'newsletter' => 0,
			'club_card' => 0,
		];
	}
}
