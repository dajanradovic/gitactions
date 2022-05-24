<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Discount;
use Illuminate\Support\Str;
use App\Services\Orders\Discounts\DiscountReturnObject;
use App\Services\Orders\Discounts\CouponCodeDiscountService;

class CouponCodeDiscountServiceTest extends TestCase
{
	public function testApplyMethodWillReturnSuccess(): void
	{
		$discount = Discount::factory()->make(['amount' => 20.0, 'is_percentage' => true, 'id' => (string) Str::uuid(), 'type' => Discount::GENERAL_DISCOUNT]);

		$product = Product::factory()->make(['price' => 200]);

		$couponCodeDiscountService = $this->getMockBuilder(CouponCodeDiscountService::class)
						->onlyMethods(['isDiscountApplicable'])
						->setConstructorArgs([new DiscountReturnObject])
						->getMock();

		$couponCodeDiscountService->method('isDiscountApplicable')->willReturn($discount);

		$response = $couponCodeDiscountService->apply($product, '123');

		$this->assertEquals(['discount_name' => $discount->title,
			'discount_id' => $discount->id,
			'type' => $discount->getType(),
			'amount' => 40.0], $response);
	}

	public function testApplyMethodWillReturnEmptyArrayBecauseCodeIsNull(): void
	{
		$discount = Discount::factory()->make(['amount' => 20.0, 'is_percentage' => true, 'id' => (string) Str::uuid(), 'type' => Discount::GENERAL_DISCOUNT]);

		$product = Product::factory()->make(['price' => 200]);

		$couponCodeDiscountService = $this->getMockBuilder(CouponCodeDiscountService::class)
						->onlyMethods(['isDiscountApplicable'])
						->setConstructorArgs([new DiscountReturnObject])
						->getMock();

		$couponCodeDiscountService->method('isDiscountApplicable')->willReturn($discount);

		$response = $couponCodeDiscountService->apply($product, null);

		$this->assertEquals([], $response);
	}
}
