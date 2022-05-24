<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Validation\Rule;
use App\Observers\FilterObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Contracts\HasTranslations as ContractsHasTranslations;

class Filter extends Model implements ContractsHasTranslations
{
	use HasTranslations;

	public const FILTER_TYPE_TEXT = 'text';
	public const FILTER_TYPE_NUMBER = 'number';
	public const FILTER_TYPE_EMAIL = 'email';
	public const FILTER_TYPE_TEL = 'tel';
	public const FILTER_TYPE_URL = 'url';
	public const FILTER_TYPE_COLOR = 'color';
	public const FILTER_TYPE_RANGE = 'range';
	public const FILTER_TYPE_TEXTAREA = 'textarea';
	public const FILTER_TYPE_SELECT = 'select';

	protected $casts = [
		'active' => 'boolean',
		'required' => 'boolean',
		'searchable' => 'boolean',
	];

	public function scopeIncludes(object $query, ?string $lang = null): Builder
	{
		return $query->translationIncludes($lang);
	}

	public function categoryFilters(): HasMany
	{
		return $this->hasMany(CategoryFilter::class);
	}

	public function defaultTranslationLocale(): string
	{
		return 'hr';
	}

	public function getAvailableOperands(): array
	{
		switch ($this->type) {
			case self::FILTER_TYPE_NUMBER:
			case self::FILTER_TYPE_RANGE:

				return ['=', '<', '>', '<=', '>=', '<>'];

			case self::FILTER_TYPE_TEXT:
			case self::FILTER_TYPE_EMAIL:
			case self::FILTER_TYPE_TEL:
			case self::FILTER_TYPE_URL:
			case self::FILTER_TYPE_COLOR:
			case self::FILTER_TYPE_TEXTAREA:

				return ['=', '<>', 'like'];

			case self::FILTER_TYPE_SELECT:

				return ['='];

			default:

				return ['=', '<', '>', '<=', '>=', '<>', 'like'];

		}
	}

	public static function getAvailableFilterTypes(): array
	{
		return [
			self::FILTER_TYPE_TEXT,
			self::FILTER_TYPE_NUMBER,
			self::FILTER_TYPE_EMAIL,
			self::FILTER_TYPE_TEL,
			self::FILTER_TYPE_URL,
			self::FILTER_TYPE_COLOR,
			self::FILTER_TYPE_RANGE,
			self::FILTER_TYPE_TEXTAREA,
			self::FILTER_TYPE_SELECT,
		];
	}

	public function getValidationRules(?string $lang = null): array
	{
		$required = $this->required ? 'required' : 'nullable';
		$min = $this->min ?? 0;
		$max = $this->max ?? 500;

		switch ($this->type) {
			case self::FILTER_TYPE_NUMBER:
			case self::FILTER_TYPE_RANGE:

				return [$required, $this->step ? 'numeric' : 'integer', 'min:' . $min, 'max:' . ($this->max ?? PHP_INT_MAX)];

			case self::FILTER_TYPE_EMAIL:

				return [$required, 'email', 'min:' . $min, 'max:' . $max];

			case self::FILTER_TYPE_URL:

				return [$required, 'url', 'min:' . $min, 'max:' . $max];

			case self::FILTER_TYPE_SELECT:

				return [$required, Rule::in($this->getFilterValue($lang))];

			default:

				return [$required, 'string', 'min:' . $min, 'max:' . $max];

		}
	}

	public function getFilterValue(?string $lang = null): string|array|null
	{
		$value = $this->determineTranslation($lang, 'value');

		return $this->type != self::FILTER_TYPE_SELECT ? $value : preg_split("/(\r\n|\n|\r)/", $value ?? '');
	}

	public function getTestFilterValue(?string $lang = null): string|array|null
	{
		$value = $this->determineTranslation('en', 'value');

		return $this->type != self::FILTER_TYPE_SELECT ? $value : preg_split("/(\r\n|\n|\r)/", $value ?? '');
	}

	public function scopeAvailable(Builder $query): Builder
	{
		return $query->where('active', true);
	}

	public function scopeSearchable(object $query): Builder
	{
		return $query->available()->where('searchable', true);
	}

	public function setNameAttribute(string $value): void
	{
		$this->attributes['name'] = preg_replace('/\s+/', ' ', $value);
	}

	public function setMessageAttribute(?string $value): void
	{
		$this->attributes['message'] = $value ? preg_replace('/\s+/', ' ', $value) : null;
	}

	public function setValueAttribute(?string $value): void
	{
		switch ($this->attributes['type']) {
			case self::FILTER_TYPE_TEXTAREA:
				$this->attributes['value'] = $value;

				break;

			case self::FILTER_TYPE_SELECT:
				$this->attributes['value'] = $this->trimSelectValues($value);

				break;

			default:
				$this->attributes['value'] = $value ? preg_replace('/\s+/', ' ', $value) : null;

		}
	}

	/**
	 * This method trims provided string and removes unwanted multiple whitespaces except new lines.
	 */
	public function trimSelectValues(?string $value): string
	{
		// https://stackoverflow.com/questions/3469080/match-whitespace-but-not-newlines
		$value = preg_replace('/(?:(?![\n\r])\s)+/', ' ', $value ?? '');
		$value = explode("\n", $value ?? '');

		foreach ($value as &$val) {
			$val = trim($val);
		}

		return implode("\n", array_filter($value));
	}

	protected static function initObservers(): ?string
	{
		return FilterObserver::class;
	}
}
