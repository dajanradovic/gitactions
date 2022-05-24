<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Category
 *
 * @property-read int|null $children_count
 * */
class CategoryResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 */
	public function toArray($request): array
	{
		$lang = $request->lang;

		return [
			'id' => $this->id,
			'name' => $this->determineTranslation($lang, 'name'),
			'slug' => $this->determineTranslation($lang, 'slug'),
			'use_parent_filters' => $this->use_parent_filters,
			'categories_count' => $this->children_count ?? $this->children()->available()->count(),
			'description' => $this->determineTranslation($lang, 'description'),
			'media' => $this->storage()->getFirstThumb('image'),
			'adult_only' => $this->adult_only,
			'extra_costs' => $this->extra_costs,
			'created_at' => formatTimestamp($this->created_at),
			'updated_at' => formatTimestamp($this->updated_at),
			'filters' => FilterCategoryResource::collection($this->getFilters($lang)),

		];
	}
}
