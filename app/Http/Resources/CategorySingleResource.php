<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Category
 *
 * @property-read int|null $children_count
 * */
class CategorySingleResource extends JsonResource
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
			'description' => $this->determineTranslation($lang, 'description'),
			'categories_count' => $this->children_count ?? $this->children()->available()->count(),
			'use_parent_filters' => $this->use_parent_filters,
			'filters' => FilterCategoryResource::collection($this->getFilters($lang)),
			'media' => $this->storage()->getFirstThumb('image'),
			'created_at' => formatTimestamp($this->created_at),
			'updated_at' => formatTimestamp($this->updated_at)
		];
	}
}
