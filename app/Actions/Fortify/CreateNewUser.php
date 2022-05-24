<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
	use PasswordValidationRules;

	/**
	 * Validate and create a newly registered user.
	 */
	public function create(array $input): User
	{
		if (!setting('registration_active')) {
			abort(403);
		}

		Validator::make($input, [
			'name' => ['required', 'string', 'max:50'],
			'email' => ['required', 'email', 'max:50', 'unique:' . User::class],
			'password' => $this->passwordRules(),
			'timezone' => ['required', 'timezone'],
			'locale' => ['nullable', 'string', Rule::in(config('custom.locales'))],
			'avatar' => ['nullable', 'url', 'max:1000'],
			'oauth_driver' => ['nullable', 'string', 'max:20', 'required_with:oauth_id'],
			'oauth_id' => ['nullable', 'string', 'max:128', 'required_with:oauth_driver'],
		])->validate();

		$data = [
			'name' => $input['name'],
			'email' => $input['email'],
			'password' => $input['password'],
			'role_id' => setting('registration_role_id'),
			'timezone' => $input['timezone'],
			'locale' => $input['locale'] ?? null,
			'avatar' => $input['avatar'] ?? null
		];

		if (!empty($input['oauth_driver'])) {
			$data[(string) $input['oauth_driver']] = $input['oauth_id'];
		}

		return Admin::create()->authParent()->create($data);
	}
}
