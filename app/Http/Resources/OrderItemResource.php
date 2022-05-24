<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\OrderItem
 * */
class OrderItemResource extends JsonResource
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
			'quantity' => $this->quantity,
			'price' => $this->price,
			'total_price' => $this->total_price,
			'total_price_minus_discounts' => $this->total_price_minus_discounts,
			'tax_rate' => $this->tax_rate,
			'tax' => $this->tax,
			'discount_amount' => $this->discount_amount,
			'discounts_applied' => $this->discounts_applied,
			'created_at' => formatTimestamp($this->created_at),
			'updated_at' => formatTimestamp($this->updated_at),
		];
	}
}
