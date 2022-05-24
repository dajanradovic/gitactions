<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\ProductVariant;
use App\Services\Orders\Calculation;
use App\Services\Orders\OrderCalculationService;
use App\Services\Orders\Discounts\GeneralDiscountService;
use App\Services\Orders\Discounts\ClubPointsDiscountService;
use App\Services\Orders\Discounts\CouponCodeDiscountService;
use App\Services\Orders\Shipping\ShippingCalculationService;
use App\Services\Orders\Discounts\AboveSetPriceDiscountService;

class OrderCalculationServiceClassTest extends TestCase
{

	public function testGetItemBasicDetails():void
	{
		$generalDiscountService = $this->createMock(GeneralDiscountService::class);
		$couponCodeDiscountService = $this->createMock(CouponCodeDiscountService::class);
		$clubPointsDiscountService = $this->createMock(ClubPointsDiscountService::class);
		$shippingCalculationService = $this->createMock(ShippingCalculationService::class);
		$aboveSetPriceDiscountService = $this->createMock(AboveSetPriceDiscountService::class);

		$calculation = new Calculation;

		$orderCalculationService = $this->getMockBuilder(OrderCalculationService::class)
						->onlyMethods([])
						->setConstructorArgs([$calculation,
											$generalDiscountService,
											$couponCodeDiscountService,
											$clubPointsDiscountService,
											$shippingCalculationService,
											$aboveSetPriceDiscountService])
						->getMock();

		$variant = ProductVariant::factory()->make();

		$orderCalculationService->getItemBasicDetails($variant);

		$expectedOutput = [
			1 => [
				'item_id' => $variant->id,
				'item_name' => $variant->name,
				'item_weight' => $variant->weight,
				'parent_id' => $variant->product_id,
			]
		];

		$this->assertEquals($expectedOutput, $calculation->getItems());	}
}
