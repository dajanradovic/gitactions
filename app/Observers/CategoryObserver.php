<?php

namespace App\Observers;

use App\Models\Category;

class CategoryObserver
{
	public function created(Category $category): void
	{
		$category->storage()->handle();
	}

	public function deleting(Category $category): void
	{
		foreach ($category->children as $child) {
			$child->delete();
		}

		foreach ($category->categoryFilters as $category_filter) {
			$category_filter->delete();
		}

		foreach ($category->vatRates as $vat_rate) {
			$vat_rate->delete();
		}
	}
}
