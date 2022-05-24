<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Spatie\LaravelSettings\Models\SettingsProperty;

class Setting extends SettingsProperty
{
	use UsesUuid;

	public const CURRENCY_KUNA = 1;
	public const CURRENCY_EURO = 2;

	protected $guarded = ['id', self::CREATED_AT, self::UPDATED_AT];

	public static function getMainCurrency(): string
	{
		return setting('main_currency') == self::CURRENCY_KUNA ? __('settings.currency-kuna') : __('settings.currency-euro');
	}

	public static function currencies(): array
	{
		return [
			self::CURRENCY_KUNA,
			self::CURRENCY_EURO
		];
	}

	public static function getCurrency(int $currency): string
	{
		return $currency == self::CURRENCY_KUNA ? __('settings.currency-kuna') : __('settings.currency-euro');
	}
}
