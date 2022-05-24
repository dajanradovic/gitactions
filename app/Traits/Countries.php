<?php

namespace App\Traits;

trait Countries
{
	public static string $homeCountry = 'HR';

	public static array $countries = [

		'Albania' => 'AL',
		'Austria' => 'AT',
		'Belgium' => 'BE',
		'Bosnia and Herzegovina' => 'BA',
		'Bulgaria' => 'BG',
		'Croatia' => 'HR',
		'Cyprus' => 'CY',
		'Czech Republic' => 'CZ',
		'Denmark' => 'DK',
		'Estonia' => 'EE',
		'Finland' => 'FI',
		'France' => 'FR',
		'Germany' => 'DE',
		'Greece' => 'EL',
		'Hungary' => 'HU',
		'Iceland' => 'IC',
		'Ireland' => 'IE',
		'Italy' => 'IT',
		'Latvia' => 'LV',
		'Luxembourg' => 'LU',
		'Malta' => 'MT',
		'Montenegro' => 'ME',
		'Netherlands' => 'NL',
		'North Macedonia' => 'MK',
		'Norway' => 'NO',
		'Poland' => 'PL',
		'Portugal' => 'PT',
		'Romania' => 'RO',
		'Russia' => 'RU',
		'Serbia' => 'RS',
		'Slovakia' => 'SK',
		'Slovenia' => 'SI',
		'Spain' => 'ES',
		'Sweden' => 'SE',
		'Switzerland' => 'CF',
		'Turkey' => 'TR',
		'United Kingdom' => 'UK',
		'United States' => 'US',
	];

	public static function getCountries(): array
	{
		ksort(self::$countries);

		return self::$countries;
	}

	public static function getCountryFullName(string $code): string|bool
	{
		return array_search($code, self::$countries);
	}
}
