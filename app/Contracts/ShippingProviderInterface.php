<?php

namespace App\Contracts;

use App\Models\Address;

interface ShippingProviderInterface
{
	public function calculate(float|string $weight, Address $address): array;

	public function basePrice(float $weight, string $country): self;

	public function islandsAdditionalCosts(string $country, string $zip_code): self;

	public function additionalCosts(): self;

	public function output(): array;

	public function createTrackingLink(string $trackingLink): string;
}
