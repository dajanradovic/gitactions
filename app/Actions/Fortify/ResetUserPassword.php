<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

class ResetUserPassword implements ResetsUserPasswords
{
	use PasswordValidationRules;

	/**
	 * Validate and reset the user's forgotten password.
	 */
	public function reset($user, array $input): void
	{
		Validator::make($input, [
			'password' => $this->passwordRules(),
		])->validate();

		$user->update([
			'password' => $input['password'],
		]);
	}
}
