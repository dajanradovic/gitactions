<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VatRate>
 */
class VatRateFactory extends Factory
{
	/**
	 * Define the model's default state.
	 */
	public function definition(): array
	{
		$countries = Address::getCountries();

		return [
			'country_code' => $countries[array_rand($countries)],
			'category_id' => Category::factory()->create()->id,
			'amount' => $this->faker->randomNumber(2, true)

		];
	}
}
