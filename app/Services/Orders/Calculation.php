<?php

namespace App\Services\Orders;

use App\Models\VatRate;

class Calculation
{
	private int $itemsCount = 1;
	private array $items = [];
	private array $shipping = ['price' => 0, 'number_of_packages' => 0];
	private array $orderTotal = [];

	public function addToItems(string|array $field, mixed $value): self
	{
		if (is_array($field)) {
			[$field] = $field;
			$this->items[$this->itemsCount][$field][] = $value;
		} else {
			$this->items[$this->itemsCount][$field] = $value;
		}

		return $this;
	}

	public function shipping(?array $shippingData): void
	{
		if ($shippingData) {
			$this->shipping = $shippingData;
		}
	}

	public function increaseItemsCount(): void
	{
		$this->itemsCount++;
	}

	public function setItemsCount(int $number): void
	{
		$this->itemsCount = $number;
	}

	public function totalSingleItem(): void
	{
		$this->items[$this->itemsCount]['total_price'] = $this->itemTotalPrice();

		if ($this->doDiscountsExist()) {
			$this->items[$this->itemsCount]['total_discounts'] = $this->itemTotalDiscounts();
			$this->items[$this->itemsCount]['total_price_with_discounts'] = $this->itemTotalPriceWithDiscounts();
		}
	}

	public function calculateItemTax(float $vatRate, string $field = 'tax'): void
	{
		$this->items[$this->itemsCount]['tax_rate'] = $vatRate . ' %';
		$this->items[$this->itemsCount]['total_tax'] = $this->itemTotalTax($vatRate);
	}

	public function total(): self
	{
		// $this->orderTotal['items'] = $this->items;

		$this->orderTotalQuantity()
			  ->orderTotalPrice()
			  ->orderTotalDiscounts()
			  ->orderTotalPriceMinusDiscounts()
			  ->orderTotalTax()
			  ->applyShipping();

		return $this;
	}

	public function getTotalPriceWithDiscountsBeforeShipping(): string
	{
		return $this->total()->orderTotal['order_price_minus_discounts'];
	}

	public function getItems(): array
	{
		return $this->items;
	}

	public function getTotal(): array
	{
		return $this->orderTotal;
	}

	private function doDiscountsExist(): bool
	{
		return isset($this->items[$this->itemsCount]['discounts']);
	}

	private function itemTotalPrice(): string
	{
		return bcmul($this->items[$this->itemsCount]['price'], $this->items[$this->itemsCount]['quantity'], 2);
	}

	private function itemTotalDiscounts(): string
	{
		$discounts = '0';

		if (isset($this->items[$this->itemsCount]['discounts'])) {
			foreach ($this->items[$this->itemsCount]['discounts'] as $discount) {
				$discounts = bcadd($discounts, $discount['amount'], 2);
			}
		}

		return bcmul($discounts, $this->items[$this->itemsCount]['quantity'], 2);
	}

	private function itemTotalPriceWithDiscounts(): string
	{
		return bcsub($this->items[$this->itemsCount]['total_price'], $this->items[$this->itemsCount]['total_discounts'] ?? '0', 2);
	}

	private function itemTotalTax(int|float $vatRate): string
	{
		return $this->doDiscountsExist() ? VatRate::calculateTax($this->items[$this->itemsCount]['total_price_with_discounts'], $vatRate)
			: VatRate::calculateTax($this->items[$this->itemsCount]['total_price'], $vatRate);
	}

	private function applyShipping(): self
	{
		$this->orderTotal['shipping'] = $this->shipping;

		if (isset($this->orderTotal['order_price_minus_discounts'])) {
			$this->orderTotal['final_price_with_shipping_added'] = bcadd($this->orderTotal['order_price_minus_discounts'], $this->shipping['price'], 2);
		} else {
			$this->orderTotal['final_price_with_shipping_added'] = bcadd($this->orderTotal['order_price'], $this->shipping['price'], 2);
		}

		return $this;
	}

	private function orderTotalQuantity(): self
	{
		$this->orderTotal['total_items_quantity'] = round(collect($this->items)->sum('quantity'), 2);

		return $this;
	}

	private function orderTotalPrice(): self
	{
		$result = '0';

		foreach ($this->items as $item) {
			$result = bcadd($result, $item['total_price'], 2);
		}

		$this->orderTotal['order_price'] = $result;

		return $this;
	}

	private function orderTotalDiscounts(): self
	{
		$result = '0';

		foreach ($this->items as $item) {
			$result = bcadd($result, $item['total_discounts'] ?? '0', 2);
		}

		$this->orderTotal['order_discounts'] = $result;

		return $this;
	}

	private function orderTotalTax(): self
	{
		$result = '0';

		foreach ($this->items as $item) {
			$result = bcadd($result, $item['total_tax'], 2);
		}

		$this->orderTotal['order_tax'] = $result;

		return $this;
	}

	private function orderTotalPriceMinusDiscounts(): self
	{
		$amount = 0;

		collect($this->items)->each(function ($item) use (&$amount) {
			if (isset($item['total_price_with_discounts'])) {
				$amount = bcadd($amount, $item['total_price_with_discounts'], 2);
			} else {
				$amount = bcadd($amount, $item['total_price'], 2);
			}
		});

		$this->orderTotal['order_price_minus_discounts'] = $amount;

		return $this;
	}
}
