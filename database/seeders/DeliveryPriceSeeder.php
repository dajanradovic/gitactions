<?php

namespace Database\Seeders;

use App\Models\DeliveryPrice;
use Illuminate\Database\Seeder;

class DeliveryPriceSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		foreach (DeliveryPrice::getCountries() as $country) {
			DeliveryPrice::create([
				'country_code' => $country,
				'delivery_service' => DeliveryPrice::DPD
			]);

			DeliveryPrice::create([
				'country_code' => $country,
				'delivery_service' => DeliveryPrice::GLS
			]);
		}
	}
}
