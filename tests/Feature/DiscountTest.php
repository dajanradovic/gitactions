<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Discount;

class DiscountTest extends TestCase
{
	private ?User $user = null;

	public function setUp(): void
	{
		parent::setUp();
		$this->user = $this->getUser();
	}

	public function testCreateDiscountSuccessfully(): void
	{
		$discount = Discount::factory(['active' => false])->make();

		$response = $this->actingAs($this->user)->post(route('discounts.store'), $discount->toArray());

		$this->assertDatabaseHas('discounts', [
			'title' => $discount['title'],
			'amount' => $discount['amount']
		]);

		$response->assertStatus(302);
	}

	public function testCreateDiscountValidationWhenTypeIsGeneralDiscount(): void
	{
		$discount = Discount::factory(['active' => true, 'type' => Discount::GENERAL_DISCOUNT])->make();

		$response = $this->actingAs($this->user)->post(route('discounts.store'), $discount->toArray());

		$response->assertSessionHasErrors(['products', 'categories']);
	}

	public function testCreateDiscountSuccessfullyWithProducts(): void
	{
		$discount = Discount::factory(['active' => true, 'type' => Discount::GENERAL_DISCOUNT])->make();

		$products = Product::factory(2)->create()->pluck('id')->toArray();

		$data = array_merge(['products' => $products], $discount->toArray());

		$response = $this->actingAs($this->user)->post(route('discounts.store'), $data);

		$this->assertDatabaseHas('discounts', [
			'title' => $discount['title'],
			'amount' => $discount['amount']
		]);

		$discount2 = Discount::where('title', $discount['title'])->first();

		foreach ($products as $id) {
			$this->assertDatabaseHas('discount_items', [
				'item_id' => $id,
				'item_type' => Product::class,
				'discount_id' => $discount2->id
			]);
		}

		$this->assertCount(count($products), $discount2->items);

		$response->assertStatus(302);
	}

	public function testCreateDiscountSuccessfullyWithCategories(): void
	{
		$discount = Discount::factory(['active' => true, 'type' => Discount::GENERAL_DISCOUNT])->make();

		$categories = Category::factory(5)->create()->pluck('id')->toArray();

		$data = array_merge(['categories' => $categories], $discount->toArray());

		$response = $this->actingAs($this->user)->post(route('discounts.store'), $data);

		$this->assertDatabaseHas('discounts', [
			'title' => $discount['title'],
			'amount' => $discount['amount']
		]);

		$discount = Discount::where('title', $discount['title'])->first();

		foreach ($categories as $id) {
			$this->assertDatabaseHas('discount_items', [
				'item_id' => $id,
				'item_type' => Category::class,
				'discount_id' => $discount->id
			]);
		}

		$this->assertCount(count($categories), $discount->items);

		$response->assertStatus(302);
	}

	public function testCreateDiscountSuccessfullyWithCategoriesAndProducts(): void
	{
		$discount = Discount::factory(['active' => true, 'type' => Discount::GENERAL_DISCOUNT])->make();

		$categories = Category::factory(2)->create()->pluck('id')->toArray();

		$products = Product::factory(2)->create()->pluck('id')->toArray();

		$data = array_merge(['categories' => $categories, 'products' => $products], $discount->toArray());

		$response = $this->actingAs($this->user)->post(route('discounts.store'), $data);

		$this->assertDatabaseHas('discounts', [
			'title' => $discount['title'],
			'amount' => $discount['amount'],
		]);

		$discount = Discount::where('title', $discount['title'])->first();

		foreach ($categories as $id) {
			$this->assertDatabaseHas('discount_items', [
				'item_id' => $id,
				'item_type' => Category::class,
				'discount_id' => $discount->id
			]);
		}

		foreach ($products as $id) {
			$this->assertDatabaseHas('discount_items', [
				'item_id' => $id,
				'item_type' => Product::class,
				'discount_id' => $discount->id
			]);
		}

		$this->assertCount(count(array_merge($categories, $products)), $discount->items);

		$response->assertStatus(302);
	}

	public function testIfDiscountIsApplicable(): void
	{
		$discount = Discount::factory(['active' => true, 'period_from' => null, 'period_to' => null, 'code' => null])->create();

		$result = Discount::where('id', $discount->id)->applicable()->get();

		$this->assertCount(1, $result);
	}

	public function testIfDiscountIsNotApplicable(): void
	{
		$discount = Discount::factory(['active' => false, 'period_from' => null, 'period_to' => null, 'code' => null])->create();

		$result = Discount::where('id', $discount->id)->applicable()->get();

		$this->assertEmpty($result);
	}

	public function testDiscountShouldBeApplicableOnlyFromDateSet(): void
	{
		$discount = Discount::factory(['active' => true, 'period_from' => now()->subDay(), 'period_to' => null, 'code' => null])->create();

		$result = Discount::where('id', $discount->id)->applicable()->get();

		$this->assertCount(1, $result);
	}

	public function testDiscountShouldNotBeApplicableFromDateBecauseFromDateIsInTheFuture(): void
	{
		$discount = Discount::factory(['active' => true, 'period_from' => now()->addDay(), 'period_to' => null, 'code' => null])->create();

		$result = Discount::where('id', $discount->id)->applicable()->get();

		$this->assertEmpty($result);
	}

	public function testDiscountShouldBeApplicableFromDateSetAndToDateSate(): void
	{
		$discount = Discount::factory(['active' => true, 'period_from' => now()->subDay(), 'period_to' => now()->addDay(), 'code' => null])->create();

		$result = Discount::where('id', $discount->id)->applicable()->get();

		$this->assertCount(1, $result);
	}

	public function testDiscountShouldNotBeApplicableBecauseDatesHavePassed(): void
	{
		$discount = Discount::factory(['active' => true, 'period_from' => now()->subDays(10), 'period_to' => now()->subDays(2), 'code' => null])->create();

		$result = Discount::where('id', $discount->id)->applicable()->get();

		$this->assertEmpty($result);
	}

	public function testCreateDiscountCouponSuccessfully(): void
	{
		$discount = Discount::factory(['active' => false])->make();

		$response = $this->actingAs($this->user)->post(route('discounts.store-coupons'), $discount->toArray());
		$this->assertDatabaseHas('discounts', [
			'title' => $discount['title'],
			'code' => $discount['code'],
			'max_use' => $discount['max_use']
		]);

		$response->assertStatus(302);
	}
}
