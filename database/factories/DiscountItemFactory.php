<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Discount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DiscountItem>
 */
class DiscountItemFactory extends Factory
{
	public function definition(): array
	{
		return [
			'discount_id' => Discount::factory()->create()->id,
			'item_type' => Product::class,
			'item_id' => Product::factory()->create()->id
		];
	}
}
