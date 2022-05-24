<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Discount;

class ProductTest extends TestCase
{
	public function testApplyDiscountNotPercentage(): void
	{
		$product = Product::factory(['price' => 100])->make();

		$discount = Discount::factory(['amount' => 10, 'is_percentage' => false])->make();

		$this->assertEquals(90, $product->applyDiscount($discount));
	}

	public function testApplyDiscountWithPercentage(): void
	{
		$product = Product::factory(['price' => 80])->make();

		$discount = Discount::factory(['amount' => 20, 'is_percentage' => true])->make();

		$this->assertEquals(64, $product->applyDiscount($discount));
	}

	public function testApplyDiscountWithPercentageWrongResult(): void
	{
		$product = Product::factory(['price' => 80])->make();

		$discount = Discount::factory(['amount' => 20, 'is_percentage' => true])->make();

		$this->assertNotEquals(66, $product->applyDiscount($discount));
	}

	public function testApplyDiscountNotPercentageWrongResult(): void
	{
		$product = Product::factory(['price' => 100])->make();

		$discount = Discount::factory(['amount' => 10, 'is_percentage' => false])->make();

		$this->assertNotEquals(80, $product->applyDiscount($discount));
	}

	public function testApplyDiscountWhenThereIsNoDiscountApplicable(): void
	{
		$product = Product::factory(['price' => 100])->make();

		$this->assertNull($product->applyDiscount());
	}

	public function testFetchDynamicPiktogramsShouldContainPiktogramNew(): void
	{
		$product = Product::factory(['created_at' => now()])->make();

		$this->assertEquals(Product::DYNAMIC_PIKTOGRAM_NOVO, $product->fetchPiktograms()[0]);
	}

	public function testFetchDynamicPiktogramsShouldNotContainPiktogramNew(): void
	{
		$product = Product::factory(['created_at' => now()->subMonths(2)])->make();

		$this->assertEmpty($product->fetchPiktograms());
	}

	public function testFetchDynamicPiktogramsShouldContainPiktogramDiscount(): void
	{
		$discount = Discount::factory()->make();

		$product = Product::factory(['created_at' => now()->subMonths(2)])->make();

		$this->assertEquals(Product::DYNAMIC_PIKTOGRAM_DISCOUNT, $product->fetchPiktograms($discount)[0]);
	}

	public function testFetchDynamicPiktogramsShouldContainPiktogramDiscountAndNew(): void
	{
		$discount = Discount::factory()->make();

		$product = Product::factory(['created_at' => now()])->make();

		$this->assertEquals(Product::DYNAMIC_PIKTOGRAM_DISCOUNT, $product->fetchPiktograms($discount)[0]);
		$this->assertEquals(Product::DYNAMIC_PIKTOGRAM_NOVO, $product->fetchPiktograms($discount)[1]);
	}
}
