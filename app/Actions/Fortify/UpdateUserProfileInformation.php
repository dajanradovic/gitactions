<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
	/**
	 * Validate and update the given user's profile information.
	 */
	public function update($user, array $input): void
	{
		Validator::make($input, [
			'name' => ['required', 'string', 'max:50'],
			'email' => ['required', 'max:50', 'email', 'unique:' . $user::class . ',email,' . $user->id],
		])
		->validateWithBag('updateProfileInformation');

		if ($input['email'] !== $user->email && $user instanceof MustVerifyEmail) {
			$this->updateVerifiedUser($user, $input);
		} else {
			$user->update([
				'name' => $input['name'],
				'email' => $input['email'],
			]);
		}
	}

	/**
	 * Update the given verified user's profile information.
	 */
	protected function updateVerifiedUser(MustVerifyEmail $user, array $input): void
	{
		if (!($user instanceof User)) {
			return;
		}

		$user->update([
			'name' => $input['name'],
			'email' => $input['email'],
			'email_verified_at' => null,
		]);

		$user->sendEmailVerificationNotification();
	}
}
