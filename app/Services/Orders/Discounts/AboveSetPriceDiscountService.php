<?php

namespace App\Services\Orders\Discounts;

use App\Models\Discount;
use App\Contracts\DiscountableItem;
use App\Contracts\DiscountServiceInterface;

class AboveSetPriceDiscountService implements DiscountServiceInterface
{
	private DiscountReturnObject $itemObject;

	public function __construct(DiscountReturnObject $discountReturnObject, public ?int $orderAmountLimit)
	{
		$this->itemObject = $discountReturnObject;
	}

	public function apply(DiscountableItem $item, mixed ...$arguments): array
	{
		[$currentTotal] = $arguments;

		if ($discount = $this->isDiscountApplicable($item, $currentTotal)) {
			return $this->calculate($item, $discount)->details($discount)->end();
		}

		return [];
	}

	public function isDiscountApplicable(DiscountableItem $item, mixed ...$arguments): Discount|bool|null
	{
		[$currentTotal] = $arguments;

		if ($currentTotal['final_price_with_shipping_added'] > $this->orderAmountLimit) {
			$discount = $this->getAboveSetPriceDiscount();

			return $discount?->active ? $discount : false;
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

	private function getAboveSetPriceDiscount(): ?Discount
	{
		return Discount::where('type', Discount::ABOVE_SET_PRICE_DISCOUNT)->applicable()->first();
	}
}
