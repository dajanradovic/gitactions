<?php

namespace App\Rules;

use Illuminate\Http\UploadedFile;
use Illuminate\Contracts\Validation\Rule;

class SafeWebp implements Rule
{
	public function __toString(): string
	{
		return self::class;
	}

	/**
	 * Determine if the validation rule passes.
	 *
	 * @param string $attribute
	 */
	public function passes($attribute, $value): bool
	{
		$ext = null;

		if ($value instanceof UploadedFile) {
			$ext = $value->extension();
			$value = $value->path();
		} elseif (filter_var($value, FILTER_VALIDATE_URL)) {
			$ext = pathinfo($value, PATHINFO_EXTENSION);
		}

		return !$value
			|| !$ext
			|| strtolower($ext) != 'webp'
			|| !($value = file_get_contents($value))
			|| !str_contains($value, 'ANIM');
	}

	/**
	 * Get the validation error message.
	 */
	public function message(): string|array
	{
		return __('validation.custom.safe-webp');
	}
}
