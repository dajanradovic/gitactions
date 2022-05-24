<?php

namespace App\Observers;

use App\Models\CategoryFilter;

class CategoryFilterObserver
{
	/**
	 * Handle the CategoryFilter "deleted" event.
	 */
	public function deleting(CategoryFilter $categoryFilter): void
	{
		/*foreach ($categoryFilter->productFilters as $product_filter) {
			$product_filter->delete();
		}*/
	}
}
