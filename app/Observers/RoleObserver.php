<?php

namespace App\Observers;

use App\Models\Role;

class RoleObserver
{
	/**
	 * Handle the role "created" event.
	 */
	public function created(Role $role): void
	{
	}

	/**
	 * Handle the role "deleted" event.
	 */
	public function deleting(Role $role): void
	{
		$role->routes->each(function ($item): void {
			$item->delete();
		});

		$role->users()->update(['role_id' => null]);
	}

	/**
	 * Handle the role "force deleted" event.
	 */
	public function forceDeleted(Role $role): void
	{
	}

	/**
	 * Handle the role "restored" event.
	 */
	public function restored(Role $role): void
	{
	}

	/**
	 * Handle the role "updated" event.
	 */
	public function updated(Role $role): void
	{
	}
}
