<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Session
 * */
class SessionResource extends JsonResource
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
			'user_id' => $this->user_id,
			'ip_address' => $this->ip_address,
			'host' => $this->ip_address ? gethostbyaddr($this->ip_address) : null,
			'user_agent' => $this->user_agent,
			'last_activity' => formatTimestamp($this->last_activity)
		];
	}
}
