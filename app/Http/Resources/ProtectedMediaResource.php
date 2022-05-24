<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Media
 * */
class ProtectedMediaResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 */
	public function toArray($request): array
	{
		$storage = $this->model->storage();

		return [
			'id' => $storage->getFileId($this),
			'title' => $storage->getTitle($this),
			'size' => $storage->size($this),
			'mime' => $storage->getMime($this),
			'url' => $storage->protectedUrl($this),
			'thumb' => $storage->getProtectedThumb($this),
			'responsive_images' => $storage->getResponsiveImages($this, null, true),
			'created_at' => formatTimestamp($storage->createdAt($this)),
			'updated_at' => formatTimestamp($storage->lastModified($this))
		];
	}
}
