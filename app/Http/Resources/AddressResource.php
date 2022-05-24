<?php

namespace App\Http\Resources;

use App\Models\Address;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Address
 * */
class AddressResource extends JsonResource
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
			'name' => $this->name,
			'city' => $this->city,
			'street' => $this->street,
			'zip_code' => $this->zip_code,
			'type' => $this->type,
			'country' => Address::getCountryFullName($this->country_code), // ili da saljem country_code pa da si ga oni na frontu izmapiraju
			'phone' => $this->phone,
			'created_at' => formatTimestamp($this->created_at),
			'updated_at' => formatTimestamp($this->updated_at),
		];
	}
}
