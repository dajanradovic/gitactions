<?php

namespace App\Observers;

use App\Models\CheeseClub;

class CheeseClubObserver
{
	/**
	 * Handle the CheeseClub "created" event.
	 *
	 * @return void
	 */
	public function created(CheeseClub $cheeseClub)
	{
		$cheeseClub->assignIfCustomerExists();
	}

	/**
	 * Handle the CheeseClub "updated" event.
	 *
	 * @return void
	 */
	public function updated(CheeseClub $cheeseClub)
	{
		$cheeseClub->assignIfCustomerExists();
	}
}
