<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\ProductVariant
 * */
class ProductVariantResource extends JsonResource
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
			'price' => $this->price,
			'measure' => $this->measure,
			'weight' => $this->weight,
			'created_at' => formatTimestamp($this->created_at),
			'updated_at' => formatTimestamp($this->updated_at),
		];
	}
}
