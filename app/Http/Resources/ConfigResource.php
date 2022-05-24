<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Settings\GeneralSettings
 * */
class ConfigResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 */
	public function toArray($request): array
	{
		return [
			'app_scheme' => $this->app_scheme,
			'info_email' => $this->app_email,
			'min_password_length' => $this->min_pass_len,
			'max_upload_size' => $this->max_upload_size,
			'max_video_length' => $this->max_video_length,
			'onesignal_app_id' => $this->onesignal_app_id,
			'dry_run_header_name' => config('custom.dry_run_header_name'),
			'maintenance_active' => $this->maintenance_active,
			'app_versions' => [
				'ios_min' => $this->ios_min_version,
				'android_min' => $this->android_min_version,
				'ios_maintenance_min' => $this->ios_maintenance_min_version,
				'android_maintenance_min' => $this->android_maintenance_min_version,
			],
			'oauth' => [
				'github' => $this->github_active,
				'gitlab' => $this->gitlab_active,
				'bitbucket' => $this->bitbucket_active,
				'facebook' => $this->facebook_active,
				'twitter' => $this->twitter_active,
				'google' => $this->google_active,
				'linkedin' => $this->linkedin_active,
				'apple' => $this->apple_active,
			],
		];
	}
}
