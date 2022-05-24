<?php

namespace App\Models;

use App\Traits\Countries;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property string $oib
 */
class Address extends Model
{
	use Countries;

	public const DELIVERY_ADDRESS = 1;
	public const INVOICE_ADDRESS = 2;

	protected $casts = [
		'type' => 'integer'
	];

	public function scopeDelivery(Builder $query): Builder
	{
		return $query->where('type', self::DELIVERY_ADDRESS);
	}

	public function scopeInvoice(Builder $query): Builder
	{
		return $query->where('type', self::INVOICE_ADDRESS);
	}

	public static function getTypes(): array
	{
		return [
			self::DELIVERY_ADDRESS,
			self::INVOICE_ADDRESS
		];
	}

	public static function getTypeName(int $type): string
	{
		$address_types = [
			self::DELIVERY_ADDRESS => __('addresses.type-delivery'),
			self::INVOICE_ADDRESS => __('addresses.type-invoice')
		];

		return $address_types[$type];
	}

	public function tempAddressCreate(string $name, string $street, string $city, string $zip_code, string $country_code, ?string $oib = null): void
	{
		$this->name = $name;
		$this->street = $street;
		$this->city = $city;
		$this->zip_code = $zip_code;
		$this->country_code = $country_code;
		$this->oib = $oib;
	}
}
