<?php

namespace App\Observers;

use App\Models\Discount;

class DiscountObserver
{
	public function created(Discount $discount): void
	{
		$discount->storeDiscountItems();
	}
}
