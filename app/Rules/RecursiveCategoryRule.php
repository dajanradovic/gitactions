<?php

namespace App\Rules;

use App\Models\Category;
use Illuminate\Contracts\Validation\Rule;

class RecursiveCategoryRule implements Rule
{
	private ?Category $category;
	private ?string $category_id;

	/**
	 * Create a new rule instance.
	 *
	 * @return void
	 */
	public function __construct(?Category $category = null, ?string $category_id = null)
	{
		$this->category = $category;
		$this->category_id = $category_id;
	}

	public function __toString()
	{
		return self::class;
	}

	/**
	 * Determine if the validation rule passes.
	 *
	 * @param string $attribute
	 *
	 * @return bool
	 */
	public function passes($attribute, $value)
	{
		return !$this->category || !$this->category_id || !$this->category->isRecursive($this->category_id);
	}

	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function message()
	{
		return __('validation.custom.categories.recursive-rule');
	}
}
