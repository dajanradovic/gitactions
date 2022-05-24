<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\TagItem
 * */
class TagItemResource extends JsonResource
{
	public function toArray($request): TagResource
	{
		return new TagResource($this->tag);
	}
}
