<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
	public function deleting(Product $product): void
	{
		foreach ($product->productFilters as $product_filter) {
			$product_filter->delete();
		}

		foreach ($product->variants as $variant) {
			$variant->delete();
		}
	}
}
