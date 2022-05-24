<?php

namespace App\Observers;

use App\Models\Filter;

class FilterObserver
{
	/**
	 * Handle the Filter "deleted" event.
	 */
	public function deleting(Filter $filter): void
	{
		foreach ($filter->categoryFilters as $category_filter) {
			$category_filter->delete();
		}
	}
}
