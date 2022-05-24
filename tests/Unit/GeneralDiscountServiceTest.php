<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Discount;
use Illuminate\Support\Str;
use App\Services\Orders\Discounts\DiscountReturnObject;
use App\Services\Orders\Discounts\GeneralDiscountService;

class GeneralDiscountServiceTest extends TestCase
{
	public function testGeneralDiscountServiceApplyMethodDiscountIsNotApplicableAndWillReturnEmptyArray(): void
	{
		$product = Product::factory()->make(['price' => 100]);

		$generalDiscountService = $this->getMockBuilder(GeneralDiscountService::class)
						->onlyMethods(['isDiscountApplicable'])
						->setConstructorArgs([new DiscountReturnObject])
						->getMock();

		$generalDiscountService->method('isDiscountApplicable')->willReturn(false);

		$response = $generalDiscountService->apply($product);

		$this->assertEquals([], $response);
	}

	public function testGeneralDiscountServiceApplyMethodWillReturnWithSuccessValueTypeDiscount(): void
	{
		$discount = Discount::factory()->make(['amount' => 5.0, 'is_percentage' => false, 'id' => (string) Str::uuid(), 'type' => Discount::GENERAL_DISCOUNT]);

		$product = Product::factory()->make(['price' => 200]);

		$generalDiscountService = $this->getMockBuilder(GeneralDiscountService::class)
						->onlyMethods(['isDiscountApplicable'])
						->setConstructorArgs([new DiscountReturnObject])
						->getMock();

		$generalDiscountService->method('isDiscountApplicable')->willReturn($discount);

		$response = $generalDiscountService->apply($product);

		$this->assertEquals(['discount_name' => $discount->title,
			'discount_id' => $discount->id,
			'type' => $discount->getType(),
			'amount' => $discount->amount], $response);
	}

	public function testGeneralDiscountServiceApplyMethodWillReturnWithSuccessPercentageTypeDiscount(): void
	{
		$discount = Discount::factory()->make(['amount' => 5.0, 'is_percentage' => true, 'id' => (string) Str::uuid(), 'type' => Discount::GENERAL_DISCOUNT]);

		$product = Product::factory()->make(['price' => 200]);

		$generalDiscountService = $this->getMockBuilder(GeneralDiscountService::class)
						->onlyMethods(['isDiscountApplicable'])
						->setConstructorArgs([new DiscountReturnObject])
						->getMock();

		$generalDiscountService->method('isDiscountApplicable')->willReturn($discount);

		$response = $generalDiscountService->apply($product);

		$this->assertEquals(['discount_name' => $discount->title,
			'discount_id' => $discount->id,
			'type' => $discount->getType(),
			'amount' => 10.0], $response);
	}
}
