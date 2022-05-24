<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Category::create([
			'name' => 'nepoznati',
			'slug' => 'nepoznato',
			'group_code' => '100',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);

		Category::create([
			'name' => 'ambalaža',
			'slug' => 'ambalaža',
			'group_code' => '101',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);
		Category::create([
			'name' => 'čaj',
			'slug' => 'čaj',
			'group_code' => '102',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);

		Category::create([
			'name' => 'dezinfekcijska sredstva',
			'slug' => 'dezinfekcijsa-sredstva',
			'group_code' => '103',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);

		Category::create([
			'name' => 'knjige',
			'slug' => 'knjige',
			'group_code' => '104',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);

		Category::create([
			'name' => 'likeri',
			'slug' => 'likeri',
			'group_code' => '105',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => true,
			'extra_costs' => 0
		]);

		Category::create([

			'name' => 'maslaci',
			'slug' => 'maslaci',
			'group_code' => '106',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);
		Category::create([
			'name' => 'masline',
			'slug' => 'masline',
			'group_code' => '107',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);
		Category::create([
			'name' => 'med',
			'slug' => 'med',
			'group_code' => '108',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);
		Category::create([
			'name' => 'm. proizvodi',
			'slug' => 'm-proizvodi',
			'group_code' => '109',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);
		Category::create([
			'name' => 'ostalo',
			'slug' => 'ostalo',
			'group_code' => '110',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);
		Category::create([
			'name' => 'pekmez',
			'slug' => 'pekmez',
			'group_code' => '111',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);

		Category::create([
			'name' => 'pivo',
			'slug' => 'pivo',
			'group_code' => '112',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => true,
			'extra_costs' => 0
		]);

		Category::create([
			'name' => 'poklon paket',
			'slug' => 'poklon-paket',
			'group_code' => '113',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);

		Category::create([
			'name' => 'pribor',
			'slug' => 'pribor',
			'group_code' => '114',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);

		Category::create([
			'name' => 'proizvod rajčica',
			'slug' => 'proizvod-rajčica',
			'group_code' => '115',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);

		Category::create([
			'name' => 'rakija',
			'slug' => 'rakija',
			'group_code' => '116',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => true,
			'extra_costs' => 0
		]);

		Category::create([
			'name' => 'riblji proizvodi',
			'slug' => 'riblji-proizvodi',
			'group_code' => '117',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);

		Category::create([
			'name' => 'sirevi',
			'slug' => 'sirevi',
			'group_code' => '118',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);

		Category::create([
			'name' => 'sirulje',
			'slug' => 'sirulje',
			'group_code' => '119',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);

		Category::create([
			'name' => 'skuta',
			'slug' => 'skuta',
			'group_code' => '120',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);

		Category::create([
			'name' => 'sladoled',
			'slug' => 'sladoled',
			'group_code' => '121',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);

		Category::create([
			'name' => 'slastice',
			'slug' => 'slastice',
			'group_code' => '122',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);

		Category::create([
			'name' => 'sokovi',
			'slug' => 'sokovi',
			'group_code' => '123',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);

		Category::create([
			'name' => 'sol',
			'slug' => 'sol',
			'group_code' => '124',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);

		Category::create([
			'name' => 'suhomesnato',
			'slug' => 'suhomesnato',
			'group_code' => '125',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);

		Category::create([
			'name' => 'suveniri',
			'slug' => 'suveniria',
			'group_code' => '126',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);

		Category::create([
			'name' => 'tartufi',
			'slug' => 'tartufi',
			'group_code' => '127',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);

		Category::create([
			'name' => 'tjestenina',
			'slug' => 'tjestenina',
			'group_code' => '128',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);

		Category::create([
			'name' => 'ulje',
			'slug' => 'ulje',
			'group_code' => '129',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);

		Category::create([
			'name' => 'vino',
			'slug' => 'vino',
			'group_code' => '130',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => true,
			'extra_costs' => 0
		]);

		Category::create([
			'name' => 'voda',
			'slug' => 'voda',
			'group_code' => '131',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);

		Category::create([
			'name' => 'vrecice',
			'slug' => 'vrecice',
			'group_code' => '132',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);

		Category::create([
			'name' => 'začini',
			'slug' => 'začini',
			'group_code' => '133',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);

		Category::create([
			'name' => 'kozmetika',
			'slug' => 'kozmetika',
			'group_code' => '134',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);

		Category::create([
			'name' => 'masti',
			'slug' => 'masti',
			'group_code' => '135',
			'active' => true,
			'use_parent_filters' => false,
			'adult_only' => false,
			'extra_costs' => 0
		]);

		// $categories = Category::all();

		// /**
		//  * @var Category $category
		//  */
		// foreach ($categories as $category) {
		// 	$category->updateTranslations([
		// 		'name' => [
		// 			'en' => null
		// 		],
		// 		'slug' => [
		// 			'en' => null
		// 		],
		// 		'description' => [
		// 			'en' => null
		// 		]
		// 	]);
		// }
	}
}
