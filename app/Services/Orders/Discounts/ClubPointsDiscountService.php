<?php

namespace App\Services\Orders\Discounts;

use App\Models\Customer;
use App\Models\Discount;
use App\Contracts\DiscountableItem;
use App\Contracts\DiscountServiceInterface;

class ClubPointsDiscountService implements DiscountServiceInterface
{
	private DiscountReturnObject $itemObject;

	public function __construct(DiscountReturnObject $discountReturnObject)
	{
		$this->itemObject = $discountReturnObject;
	}

	public function apply(DiscountableItem $item, mixed ...$arguments): array
	{
		[$customer] = $arguments;
		// treba napraviti logiku, ideja je da se ovdje passa Customer
		if ($discount = $this->isDiscountApplicable($item)) {
			return $this->calculate($item, $discount)->details($discount)->end();
		}

		return [];
	}

	public function isDiscountApplicable(DiscountableItem $item, mixed ...$arguments): Discount|bool|null
	{
		$discount = $item->getCurrentlyApplicableDiscount();

		return false;

		/*if($discount){

			if($discount->doesAddUp() && $discountCode = $this->getCouponCodeDiscount($code)){
						// logika oko dohvatanja bodova i konverzije u popust

			}

		}
		else if(){



		}

		return false;*/
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
