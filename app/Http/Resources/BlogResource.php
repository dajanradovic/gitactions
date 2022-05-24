<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Blog
 * */
class BlogResource extends JsonResource
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
			'slug' => $this->slug,
			'title' => $this->title,
			'body' => $this->body,
			'tags' => TagItemResource::collection($this->tags),
			'published_at' => formatTimestamp($this->published_at),
			'created_at' => formatTimestamp($this->created_at),
			'updated_at' => formatTimestamp($this->updated_at),
			'media' => new MediaResource($this->storage()->getFirstFile('image'))
		];
	}
}
