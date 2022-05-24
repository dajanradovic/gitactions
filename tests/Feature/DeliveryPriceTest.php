<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\DeliveryPrice;

class DeliveryPriceTest extends TestCase
{
	public ?DeliveryPrice $deliveryPrice = null;

	public function setUp(): void
	{
		parent::setUp();

		if ($deliveryPrice = DeliveryPrice::where('country_code', 'HR')->where('delivery_service', DeliveryPrice::DPD)->first()) {
			$deliveryPrice->update(['up_to_2_kg' => 40,
				'up_to_5_kg' => 50,
				'up_to_10_kg' => 60,
				'up_to_15_kg' => 70,
				'up_to_20_kg' => 80,
				'up_to_25_kg' => 90,
				'up_to_32_kg' => 100, ]);

			$this->deliveryPrice = new DeliveryPrice;
		} else {
			$this->deliveryPrice = DeliveryPrice::factory(['delivery_service' => DeliveryPrice::DPD])->create();
		}
	}

	public function testGetPrice(): void
	{
		$result = $this->deliveryPrice->getPrice(19, 'HR', DeliveryPrice::DPD);

		$this->assertEquals(['price' => 80, 'number_of_packages' => 1], $result);
	}

	public function testGetPriceShouldBe2PackagesAndConcatenatedPrice(): void
	{
		$result = $this->deliveryPrice->getPrice(40, 'HR', DeliveryPrice::DPD);

		$this->assertEquals(['price' => 160, 'number_of_packages' => 2], $result);
	}

	public function testGetPriceShouldBe3PackagesAndConcatenatedPrice(): void
	{
		$result = $this->deliveryPrice->getPrice(65, 'HR', DeliveryPrice::DPD);

		$this->assertEquals(['price' => 240, 'number_of_packages' => 3], $result);
	}
}
