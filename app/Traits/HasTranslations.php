<?php

namespace App\Traits;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasTranslations
{
	public function getTranslation(string $column, string $locale, ?string $default = null): ?string
	{
		return $this->translations->where('column', $column)->where('locale', $locale)->first()->value ?? $default;
	}

	public function translations(): MorphMany
	{
		return $this->morphMany(Translation::class, 'item');
	}

	public function updateTranslations(array $columns = []): self
	{
		foreach ($columns as $column => $locales) {
			foreach ($locales as $locale => $value) {
				$this->translations()->updateOrCreate([
					'column' => $column,
					'locale' => $locale,
				], [
					'value' => $value,
				]);
			}
		}

		return $this;
	}

	public function scopeTranslationIncludes(object $query, ?string $lang = null): Builder
	{
		if (!$lang || ($lang == static::defaultTranslationLocale())) {
			return $query;
		}

		return $query->with(['translations' => function ($query) use ($lang) {
			$query->where('locale', $lang);
		}]);
	}

	public static function defaultTranslationLocale(): string
	{
		return 'hr';
	}

	public function determineTranslation(?string $lang, string $key): ?string
	{
		return !$lang || ($lang == static::defaultTranslationLocale()) ? $this->{$key} : $this->getTranslation($key, $lang, $this->{$key});
	}

	protected static function bootHasTranslations(): void
	{
		static::deleting(function ($model): void {
			$model->translations->each(function ($item): void {
				$item->delete();
			});
		});
	}
}
