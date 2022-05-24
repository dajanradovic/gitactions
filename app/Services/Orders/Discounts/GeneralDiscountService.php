<?php

namespace App\Services\Orders\Discounts;

use App\Models\Discount;
use App\Contracts\DiscountableItem;
use App\Contracts\DiscountServiceInterface;

class GeneralDiscountService implements DiscountServiceInterface
{
	private DiscountReturnObject $itemObject;

	public function __construct(DiscountReturnObject $discountReturnObject)
	{
		$this->itemObject = $discountReturnObject;
	}

	public function apply(DiscountableItem $item, mixed ...$arguments): array
	{
		if ($discount = $this->isDiscountApplicable($item)) {
			return $this->calculate($item, $discount)->details($discount)->end();
		}

		return [];
	}

	public function isDiscountApplicable(DiscountableItem $item, mixed ...$arguments): Discount|bool|null
	{
		return $item->getCurrentlyApplicableDiscount();
	}

	public function calculate(DiscountableItem $item, Discount $discount): self
	{
		$this->itemObject->setAmount($item->getDiscountAmount($discount));

		return $this;
	}

	public function details(Discount $discount): self
	{
		$this->itemObject->buildExtra($discount->title, $discount->id, $discount->getType());

		return $this;
	}

	public function end(): array
	{
		return $this->itemObject->output();
	}
}
