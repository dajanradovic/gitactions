<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Referral
 * */
class ReferralResource extends JsonResource
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
			'email' => $this->email,
			'has_accepted' => $this->resolved_at ? true : false,
			'created_at' => formatTimestamp($this->created_at),
			'updated_at' => formatTimestamp($this->updated_at),
		];
	}
}
