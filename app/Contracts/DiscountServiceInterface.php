<?php

namespace App\Contracts;

use App\Models\Discount;

interface DiscountServiceInterface
{
	public function apply(DiscountableItem $item, mixed ...$arguments): array;

	public function isDiscountApplicable(DiscountableItem $item, mixed ...$arguments): Discount|bool|null;

	public function calculate(DiscountableItem $item, Discount $discount): self;

	public function details(Discount $discount): self;

	public function end(): array;
}
