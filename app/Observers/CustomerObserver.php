<?php

namespace App\Observers;

use App\Models\Customer;

class CustomerObserver
{
	public function deleting(Customer $customer): void
	{
		foreach ($customer->reviews as $review) {
			$review->delete();
		}

		foreach ($customer->addresses as $address) {
			$address->delete();
		}

		foreach ($customer->likes as $like) {
			$like->delete();
		}

		foreach ($customer->referrals as $referral) {
			$referral->delete();
		}

		if ($customer->cheese_club_id) {
			$customer->cheeseclub->delete();
		}
	}
}
