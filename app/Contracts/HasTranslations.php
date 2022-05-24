<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface HasTranslations
{
	public function translations(): MorphMany;

	public function getTranslation(string $column, string $locale, ?string $default = null): ?string;

	public function updateTranslations(array $columns = []): self;
}
