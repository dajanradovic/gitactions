<?php

namespace App\Services\Orders\Discounts;

use App\Models\Discount;
use App\Contracts\DiscountableItem;
use App\Contracts\DiscountServiceInterface;

class CouponCodeDiscountService implements DiscountServiceInterface
{
	private DiscountReturnObject $itemObject;

	public function __construct(DiscountReturnObject $discountReturnObject)
	{
		$this->itemObject = $discountReturnObject;
	}

	public function apply(DiscountableItem $item, mixed ...$arguments): array
	{
		[$code] = $arguments;

		if ($code && $discount = $this->isDiscountApplicable($item, $code)) {
			return $this->calculate($item, $discount)->details($discount)->end();
		}

		return [];
	}

	public function isDiscountApplicable(DiscountableItem $item, mixed ...$arguments): Discount|bool|null
	{
		[$code] = $arguments;

		$discount = $item->getCurrentlyApplicableDiscount();

		if ($discount) {
			if ($discount->doesAddUp() && $discountCode = $this->getCouponCodeDiscount($code)) {
				if ($discountCode->items()->where('item_id', $item->id)->exists()) {
					return $discountCode;
				}
			}
		} elseif ($code && $discountCode = $this->getCouponCodeDiscount($code)) {
			if ($discountCode->items()->where('item_id', $item->id)->exists()) {
				return $discountCode;
			}
		}

		return false;
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

	private function getCouponCodeDiscount(string $code): ?Discount
	{
		return Discount::where('code', $code)->first();
	}
}
