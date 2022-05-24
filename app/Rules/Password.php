<?php

namespace App\Rules;

use Laravel\Fortify\Rules\Password as FortifyPassword;

class Password extends FortifyPassword
{
	/**
	 * Create a new rule instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->length(setting('min_pass_len'));

		if (setting('pass_uppercase_char')) {
			$this->requireUppercase();
		}

		if (setting('pass_numeric_char')) {
			$this->requireNumeric();
		}

		if (setting('pass_special_char')) {
			$this->requireSpecialCharacter();
		}
	}

	public function __toString(): string
	{
		return self::class;
	}
}
