<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\User
 * */
class UserResource extends JsonResource
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
			'allow_push_notifications' => $this->allow_push_notifications,
			'password_needs_rehash' => $this->password ? Hash::needsRehash($this->password) : false,
			'avatar' => $this->getAvatar(),
			'oauth' => [
				'github' => $this->github,
				'gitlab' => $this->gitlab,
				'bitbucket' => $this->bitbucket,
				'facebook' => $this->facebook,
				'twitter' => $this->twitter,
				'google' => $this->google,
				'linkedin' => $this->linkedin,
				'apple' => $this->apple,
			],
			'extra' => $this->user->getUserExtraData($request),
			'email_verified_at' => $this->email_verified_at ? formatTimestamp($this->email_verified_at) : null,
			'created_at' => formatTimestamp($this->created_at),
			'updated_at' => formatTimestamp($this->updated_at),
			'token' => $this->token()
		];
	}
}
