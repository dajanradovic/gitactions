<?php

namespace App\Rules;

use App\Models\Filter;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Rule as RuleContract;

class ValidateFilter implements RuleContract
{
	/**
	 * Create a new rule instance.
	 *
	 * @return void
	 */
	public function __construct(private ?Filter $filter = null, private ?string $error = null)
	{
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
		$attribute = explode('.', $attribute);

		$this->filter = Filter::findOrFail(end($attribute));

		$validator = is_array($value) ? $this->validateOperands($value) : $this->validateFilter($value);

		$this->error = $validator->errors()->first();

		return !$validator->fails();
	}

	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function message()
	{
		return __('validation.custom.filter', ['filter' => $this->filter->name, 'error' => $this->error]);
	}

	private function validateFilter(string $value): \Illuminate\Validation\Validator
	{
		return Validator::make([
			'value' => $value
		], [
			'value' => $this->filter->getValidationRules()
		]);
	}

	private function validateOperands(array $values): \Illuminate\Validation\Validator
	{
		return Validator::make([
			'operands' => array_filter(array_keys($values), function ($value) {
				return is_string($value);
			}),
			'values' => $values
		], [
			'operands' => ['array'],
			'operands.*' => ['required', 'string', Rule::in($this->filter->getAvailableOperands())],
			'values' => ['array'],
			'values.*' => $this->filter->getValidationRules()
		]);
	}
}
