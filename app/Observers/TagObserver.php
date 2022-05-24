<?php

namespace App\Observers;

use App\Models\Tag;

class TagObserver
{
	/**
	 * Handle the tag "created" event.
	 */
	public function created(Tag $tag): void
	{
	}

	/**
	 * Handle the tag "updated" event.
	 */
	public function updated(Tag $tag): void
	{
	}

	/**
	 * Handle the tag "deleted" event.
	 */
	public function deleting(Tag $tag): void
	{
		$tag->items->each(function ($item): void {
			$item->delete();
		});
	}

	/**
	 * Handle the tag "restored" event.
	 */
	public function restored(Tag $tag): void
	{
	}

	/**
	 * Handle the tag "force deleted" event.
	 */
	public function forceDeleted(Tag $tag): void
	{
	}
}
