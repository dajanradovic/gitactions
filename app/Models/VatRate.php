<?php

namespace App\Models;

class VatRate extends Model
{
	public static function calculateTax(string $price, int|float $tax): string
	{
		return bcmul($price, bcdiv((string) $tax, '100', 2), 2);
	}
}
