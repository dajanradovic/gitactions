<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\ProductFilter
 *
 * */
class ProductFilterResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return array
	 */
	public function toArray($request)
	{
		return [
			'id' => $this->id,
			'value' => $this->value,
			'filter_category' => new FilterCategoryResource($this->categoryFilter),
			'created_at' => formatTimestamp($this->created_at),
			'updated_at' => formatTimestamp($this->updated_at)
		];
	}
}
