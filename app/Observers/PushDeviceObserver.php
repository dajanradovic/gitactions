<?php

namespace App\Observers;

use App\Models\PushDevice;

class PushDeviceObserver
{
	/**
	 * Handle the push device "created" event.
	 */
	public function created(PushDevice $pushDevice): void
	{
		$pushDevice->getOneSignalDevice();
	}

	/**
	 * Handle the push device "deleted" event.
	 */
	public function deleting(PushDevice $pushDevice): void
	{
	}

	/**
	 * Handle the push device "force deleted" event.
	 */
	public function forceDeleted(PushDevice $pushDevice): void
	{
	}

	/**
	 * Handle the push device "restored" event.
	 */
	public function restored(PushDevice $pushDevice): void
	{
	}

	/**
	 * Handle the push device "updated" event.
	 */
	public function updated(PushDevice $pushDevice): void
	{
		$pushDevice->getOneSignalDevice();
	}
}
