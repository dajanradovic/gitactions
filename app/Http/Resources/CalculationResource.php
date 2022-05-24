<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @method getItems()
 * @method getTotal()
 * */
class CalculationResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 */
	public function toArray($request): array
	{
		return [
			'items' => $this->getItems(),
			'total' => $this->getTotal(),
			'created_at' => formatTimestamp(),
		];
	}
}
