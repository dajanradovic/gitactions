<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\UserActivity
 * */
class UserActivityResource extends JsonResource
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
			'item' => [
				'id' => $this->item_id,
				'type' => $this->item_type,
				'title' => $this->item->getUserActivityTitle(),
				'url' => $this->item->getUserActivityUrl(),
			],
			'user' => new UserPublicResource($this->user),
			'ip_address' => $this->ip_address,
			'type' => $this->type,
			'updated_fields' => $this->updated_fields,
			'created_at' => formatTimestamp($this->created_at),
		];
	}
}
