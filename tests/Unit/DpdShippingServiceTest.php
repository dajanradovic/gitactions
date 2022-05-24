<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Address;
use App\Models\DeliveryPrice;
use App\Services\Orders\Shipping\DpdShippingService;

class DpdShippingServiceTest extends TestCase
{
	public function testCalculateWithoutIslandCosts(): void
	{
		$address = Address::factory(['country_code' => 'HR'])->make();

		$mockcontext = $this->createMock(DeliveryPrice::class);
		$mockcontext->method('getPrice')->willReturn(['price' => 45, 'number_of_packages' => 1]);
		$mockcontext->method('getAdditionalIslandCosts')->willReturn(0);

		$dpdShippingService = new DpdShippingService($mockcontext);
		$result = $dpdShippingService->calculate(30, $address);

		$this->assertEquals(['price' => 45, 'number_of_packages' => 1], $result);
	}
}
