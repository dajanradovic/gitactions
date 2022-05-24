<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
	/**
	 * Handle the user "created" event.
	 */
	public function created(User $user): void
	{
		$user->handleShouldVerifyEmail();
	}

	/**
	 * Handle the user "deleted" event.
	 */
	public function deleting(User $user): void
	{
		$user->activities->each(function ($item): void {
			$item->delete();
		});

		$user->sessions->each(function ($item): void {
			$item->delete();
		});
	}

	public function deleted(User $user): void
	{
		if ($user->user()->exists()) {
			$user->user->delete();
		}
	}

	/**
	 * Handle the user "force deleted" event.
	 */
	public function forceDeleted(User $user): void
	{
	}

	/**
	 * Handle the user "restored" event.
	 */
	public function restored(User $user): void
	{
	}

	/**
	 * Handle the user "updated" event.
	 */
	public function updating(User $user): void
	{
		$user->detectIfEmailUpdate();
	}

	/**
	 * Handle the user "updated" event.
	 */
	public function updated(User $user): void
	{
		$user->handleEmailUpdate();
	}
}
