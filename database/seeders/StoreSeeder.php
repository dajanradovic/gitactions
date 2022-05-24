<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Store::create([
			'code' => '011',
			'department' => 'MP Kolan',
			'webshop_name' => 'Trgovina Kolan'
		]);

		Store::create([
			'code' => '013',
			'department' => 'MP Zadar',
			'webshop_name' => 'Trgovina City Gallerija'
		]);

		Store::create([
			'code' => '014',
			'department' => 'MP Zadar 2',
			'webshop_name' => 'Trgovina tržnica - poluotok'
		]);

		Store::create([
			'code' => '015',
			'department' => 'MP Spit 2',
			'webshop_name' => 'Trgovina tržnica - Sućidar'
		]);

		Store::create([
			'code' => '016',
			'department' => 'MP Split 3',
			'webshop_name' => 'Trgovina Split Grad'
		]);

		Store::create([
			'code' => '017',
			'department' => 'MP Dubrovnik',
			'webshop_name' => 'Trgovina Dubrovnik'
		]);

		Store::create([
			'code' => '018',
			'department' => 'MP Rijeka',
			'webshop_name' => 'Trgovina Rijeka - tržnica'
		]);

		Store::create([
			'code' => '019',
			'department' => 'MP Zagreb 1',
			'webshop_name' => 'Trgovina Zagreb Dolac'
		]);

		Store::create([
			'code' => '020',
			'department' => 'MP Zagreb 2',
			'webshop_name' => 'Trgovina Zagreb Jarun'
		]);

		Store::create([
			'code' => '022',
			'department' => 'MP Zagreb 4',
			'webshop_name' => 'Trgovina Cvjetni'
		]);

		Store::create([
			'code' => '023',
			'department' => 'MP Zagreb 5',
			'webshop_name' => 'Trgovina Arena'
		]);

		Store::create([
			'code' => '024',
			'department' => 'Web shop',
		]);
	}
}
