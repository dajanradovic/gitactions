<?php

namespace App\Contracts;

use App\Models\Discount;

/**
 * @property string $id
 */
interface DiscountableItem
{
	public function getCurrentlyApplicableDiscount(): ?Discount;

	public function applyDiscount(?Discount $discount = null): ?string;

	public function getDiscountAmount(?Discount $discount = null): ?string;

	public function getVatRate(string $countryCode = 'HR'): int|float;
}
