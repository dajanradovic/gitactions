<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Discount
 * */
class DiscountResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 */
	public function toArray($request): array
	{
		return [
			'id' => $this->id,
			'title' => $this->title,
			'period_from' => $this->period_from ? formatTimestamp($this->period_from) : null,
			'period_to' => $this->period_to ? formatTimestamp($this->period_to) : null,
			'amount' => $this->amount,
			'active' => $this->active,
			'add_up_with_other_discounts' => $this->add_up_with_other_discounts,
			'is_percentage' => $this->is_percentage,
			'created_at' => formatTimestamp($this->created_at),
			'updated_at' => formatTimestamp($this->updated_at),
		];
	}
}
