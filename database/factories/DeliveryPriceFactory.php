<?php

namespace Database\Factories;

use App\Models\DeliveryPrice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DeliveryPrice>
 */
class DeliveryPriceFactory extends Factory
{
	public function definition(): array
	{
		return [
			'country_code' => 'HR',
			'delivery_service' => array_rand(DeliveryPrice::getShippingServices()),
			'up_to_2_kg' => 40,
			'up_to_5_kg' => 50,
			'up_to_10_kg' => 60,
			'up_to_15_kg' => 70,
			'up_to_20_kg' => 80,
			'up_to_25_kg' => 90,
			'up_to_32_kg' => 100,
			'islands_extra' => 0,
			'additional_costs' => 0
		];
	}
}
