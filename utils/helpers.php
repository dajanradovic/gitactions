<?php

use App\Models\User;
use Illuminate\Support\Carbon;
use App\Settings\GeneralSettings;

function setting(?string $key = null, mixed $default = null): mixed
{
	try {
		$setting = app(GeneralSettings::class);

		return $key ? ($setting->{$key} ?? $default) : $setting;
	} catch (Exception) {
	}

	return $default;
}

function formatTimestamp(DateTimeInterface|string|float|int $carbon = null, ?string $format = 'Y-m-d H:i:s', DateTimeZone|string $inputTimezone = null): string
{
	return formatLocalTimestamp($carbon, $format, 'UTC', $inputTimezone);
}

function formatLocalTimestamp(DateTimeInterface|string|float|int $carbon = null, ?string $format = null, DateTimeZone|string $timezone = null, DateTimeZone|string $inputTimezone = null): string
{
	$format ??= setting('date_format') . ' ' . setting('time_format');
	$timezone ??= auth()->user()->timezone ?? setting('timezone');

	$carbon = is_numeric($carbon) ? Carbon::createFromTimestamp($carbon, $inputTimezone) : new Carbon($carbon, $inputTimezone);

	return $carbon->setTimezone($timezone)->format($format);
}

function getUser(): ?User
{
	return auth(getCurrentAuthGuard())->user();
}

function getCurrentAuthGuard(?string $default = null): ?string
{
	$guards = array_keys(config('auth.guards'));

	try {
		foreach ($guards as $guard) {
			$guard = (string) $guard;

			if (auth($guard)->check()) {
				auth()->shouldUse($guard);

				return $guard;
			}
		}
	} catch (Exception) {
	}

	return $default;
}

function renderTimezones(): array
{
	$timezones = [];

	foreach (timezone_identifiers_list() as $timezone) {
		$items = explode('/', $timezone);

		if (count($items) == 1) {
			$timezones[$timezone] = str_replace('_', ' ', $timezone);
		} else {
			$timezones[array_shift($items)][$timezone] = str_replace('_', ' ', implode(' - ', $items));
		}
	}

	return $timezones;
}

function stringifyAttr(array $attributes): string
{
	foreach ($attributes as $key => &$value) {
		if (is_bool($value)) {
			if ($value) {
				$value = $key;
			} else {
				unset($attributes[$key]);
			}
		} else {
			$value = $key . '="' . $value . '"';
		}
	}

	return implode(' ', $attributes);
}
