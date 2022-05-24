<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\PushDevice
 * */
class PushDeviceResource extends JsonResource
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
			'device_id' => $this->device_id,
			'app_version' => $this->app_version,
			'device_type' => $this->getOneSignalDeviceType(),
			'device_model' => $this->device_model,
			'device_os' => $this->device_os,
			'timezone' => $this->timezone,
			'created_at' => formatTimestamp($this->created_at),
			'updated_at' => formatTimestamp($this->updated_at),
		];
	}
}
