<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;

class CategoryTest extends TestCase
{
	public function testCreateCategoryAndVatRates(): void
	{
		$user = $this->getUser();

		$countries = [];

		foreach (Category::getCountries() as $country) {
			$countries[$country] = 0;
		}

		$data = [
			'category_id' => null,
			'name' => 'testcategory',
			'name_en' => $this->faker->name(),
			'slug' => 'test-category-slug',
			'active' => true,
			'adult_only' => false,
			'use_parent_filters' => false,
			'extra_costs' => null,
			'description' => $this->faker->text(),
			'description_en' => $this->faker()->text(),
			'countries' => $countries
		];

		$response = $this->actingAs($user)->post('categories', $data);

		$this->assertDatabaseHas('categories', [
			'name' => 'testcategory',
			'slug' => 'test-category-slug'
		]);

		$category = Category::where('slug', 'test-category-slug')->first();

		$this->assertCount(count($countries), $category->vatRates);
		$response->assertStatus(302);
	}
}
