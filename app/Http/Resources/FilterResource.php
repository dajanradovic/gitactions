<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Filter
 * */
class FilterResource extends JsonResource
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
			'display_label' => $this->determineTranslation($lang, 'display_label'),
			'value_en' => $this->getTestFilterValue(),
			'type' => $this->type,
			'min' => $this->min,
			'max' => $this->max,
			'step' => $this->step,
			'value' => $this->getFilterValue($lang),
			'required' => $this->required,
			'searchable' => $this->searchable,
			'message' => $this->determineTranslation('en', 'message'),
			'operands' => $this->getAvailableOperands(),
			'created_at' => formatTimestamp($this->created_at),
			'updated_at' => formatTimestamp($this->updated_at)
		];
	}
}
