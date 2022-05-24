<?php

namespace App\Services\Orders\Shipping;

use App\Models\Address;
use App\Contracts\ShippingProviderInterface;

class ShippingCalculationService
{
	private string $homeCountry;

	public function __construct(string $homeCountry = 'HR')
	{
		$this->homeCountry = $homeCountry;
	}

	public function decide(string $priceBeforeShipping, array $items, ShippingProviderInterface $shippingProviderInterface, ?Address $address = null): int|float|array
	{
		if ($this->determineIfFreeShippingIsPossible($priceBeforeShipping, $address->country_code)) {
			return [];
		}

		return $shippingProviderInterface->calculate($this->calculateItemsWeight($items), $address);
	}

	private function determineIfFreeShippingIsPossible(string $priceBeforeShipping, ?string $country = null): bool
	{
		if ($country == $this->homeCountry) {
			return $priceBeforeShipping > setting('gratis_delivery');
		}

		return $priceBeforeShipping > setting('gratis_delivery_ino');
	}

	private function calculateItemsWeight(array $items): string
	{
		$totalWeight = '0';

		foreach ($items as $item) {
			$totalWeight = bcadd($totalWeight, bcmul($item['item_weight'], $item['quantity'], 2), 2);
		}

		return $totalWeight;
	}
}
