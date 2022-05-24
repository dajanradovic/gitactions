<?php

namespace Database\Seeders;

use App\Models\Discount;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// create global discount for orders grater than certaion value which will be configurable in settings
		Discount::create([
			'title' => 'Discount on order total price',
			'max_use' => 0,
			'period_from' => null,
			'period_to' => null,
			'amount' => 10,
			'is_percentage' => true,
			'active' => false,
			'type' => Discount::ABOVE_SET_PRICE_DISCOUNT
		]);
	}
}
