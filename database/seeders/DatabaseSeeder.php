<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		$this->call([
			UsersTableSeeder::class,
			// StoreSeeder::class,
			CategorySeeder::class,
			DeliveryPriceSeeder::class,
			DiscountSeeder::class
		]);
	}
}
