<?php

namespace App\Services\Orders\Discounts;

class DiscountReturnObject
{
	private ?string $name = null;
	private ?string $id = null;
	private ?string $type = null;
	private ?string $amount = '0';

	public function setAmount(string $amount): void
	{
		$this->amount = $amount;
	}

	public function buildExtra(string $name, string $id, string $type): self
	{
		$this->name = $name;
		$this->id = $id;
		$this->type = $type;

		return $this;
	}

	public function output(): array
	{
		return [

			'discount_name' => $this->name,
			'discount_id' => $this->id,
			'type' => $this->type,
			'amount' => $this->amount
		];
	}
}
