<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
	public function definition(): array
	{
		$countries = Address::getCountries();

		return [
			'name' => $this->faker->name(),
			'country_code' => $countries[array_rand($countries)],
			'city' => $this->faker->city(),
			'street' => $this->faker->streetAddress(),
			'zip_code' => $this->faker->postcode(),
			'type' => $this->faker->numberBetween(1, 2),
			'phone' => '932212323'
		];
	}
}
