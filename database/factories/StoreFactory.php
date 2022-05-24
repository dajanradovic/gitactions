<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
	public function definition(): array
	{
		return [
			'code' => '454',
			'department' => 'Test store',
			'webshop_name' => 'Trgovina test store'
		];
	}
}
