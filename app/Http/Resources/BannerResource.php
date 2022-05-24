<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Banner
 *
 * */
class BannerResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 */
	public function toArray($request): array
	{
		$storage = $this->storage();

		return [
			'id' => $this->id,
			'type' => $this->type,
			'title' => $this->title,
			'subtitle' => $this->subtitle,
			'url' => $this->url,
			'media' => [
				'desktop' => new MediaResource($storage->getFirstFile('image')),
				'mobile' => new MediaResource($storage->getFirstFile('image_mobile')),
			],
			'created_at' => formatTimestamp($this->created_at),
			'updated_at' => formatTimestamp($this->updated_at),
		];
	}
}
