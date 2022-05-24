<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\User
 * */
class UserPublicResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 */
	public function toArray($request): array
	{
		return [
			'id' => $this->user->id,
			'type' => $this->user->getUserType(),
			'name' => $this->name,
			'email' => $this->email,
			'avatar' => $this->getAvatar(),
			'extra' => $this->user->getPublicUserExtraData($request),
			'created_at' => formatTimestamp($this->created_at),
			'updated_at' => formatTimestamp($this->updated_at)
		];
	}
}
