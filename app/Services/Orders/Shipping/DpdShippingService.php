<?php

namespace App\Services\Orders\Shipping;

use App\Models\Address;
use App\Models\DeliveryPrice;
use App\Contracts\ShippingProviderInterface;

class DpdShippingService implements ShippingProviderInterface
{
	public array $calculation = [];

	public function __construct(private DeliveryPrice $deliveryPrice)
	{
	}

	public function calculate(float|string $weight, Address $address): array
	{
		return $this->basePrice($weight, $address->country_code)
					->islandsAdditionalCosts($address->country_code, $address->zip_code)
					->additionalCosts()
					->output();
	}

	public function basePrice(float $weight, string $country): self
	{	
		$this->calculation = $this->deliveryPrice->getPrice($weight, $country, $this->deliveryPrice::DPD);

		return $this;
	}

	public function additionalCosts(): self
	{
		return $this;
	}

	public function islandsAdditionalCosts(string $country, string $zip_code): self
	{
		if (in_array($zip_code, $this->deliveryPrice::ISLAND_ZIP_CODES)) {
			$this->calculation['price'] = bcadd($this->calculation['price'], (string) $this->deliveryPrice->getAdditionalIslandCosts($country, $this->deliveryPrice::DPD), 2);
		}

		return $this;
	}

	public function output(): array
	{
		return $this->calculation;
	}

	public function createTrackingLink(string $shippingNumber): string
	{
		return 'https://www.dpdgroup.com/nl/mydpd/my-parcels/track?lang=en&parcelNumber=' . $shippingNumber;
	}
}
