<?php

namespace App\Services\Orders;

use App\Models\Address;
use App\Models\Product;
use App\Models\Customer;
use App\Models\ProductVariant;
use App\Contracts\DiscountableItem;
use App\Contracts\DiscountServiceInterface;
use App\Services\Orders\Shipping\ShippingServiceFactory;
use App\Services\Orders\Discounts\GeneralDiscountService;
use App\Services\Orders\Discounts\ClubPointsDiscountService;
use App\Services\Orders\Discounts\CouponCodeDiscountService;
use App\Services\Orders\Shipping\ShippingCalculationService;
use App\Services\Orders\Discounts\AboveSetPriceDiscountService;

class OrderCalculationService
{
	private Calculation $calculation;

	public function __construct(Calculation $calculation,
								private GeneralDiscountService $generalDiscountService,
								private CouponCodeDiscountService $couponCodeDiscountService,
								private ClubPointsDiscountService $clubPointsDiscountService,
								private ShippingCalculationService $shippingCalculationService,
								private AboveSetPriceDiscountService $aboveSetPriceDiscountService)
	{
		$this->calculation = $calculation;
	}

	public function calculateTotal(array $items, ?string $code = null, ?string $lang = null, ?Address $address = null, ?Customer $customer = null, ?string $shippingProvider = null): Calculation
	{
		$arrayOfItems = [];

		collect($items)->each(function ($item) use ($lang, $code, $address, &$arrayOfItems) {

			$product = $this->getItem($item);

			$arrayOfItems[] = $product;

			$this->getItemBasicDetails($product, $lang)
				->originalPrice($product->price)
				->getDiscount($this->generalDiscountService, $product)
				->getDiscount($this->couponCodeDiscountService, $product, $code)
				->getDiscount($this->clubPointsDiscountService, $product)
				->singleItemQuantity($item['quantity'])
				->totalSingleItem()
				->calculateTax($product, $address?->country_code);

			$this->calculation->increaseItemsCount();
		});

		$this->calculateShipping($shippingProvider, $address)->total();
		$this->getGlobalDiscount($this->aboveSetPriceDiscountService, $arrayOfItems, $address?->country_code);

		return $this->calculateShipping($shippingProvider, $address)->total();
	}

	public function getDiscount(DiscountServiceInterface $discountServiceInterface, DiscountableItem $item, ?string $code = null, ?Customer $customer = null, ?array $items = null): self
	{
		$discountData = $discountServiceInterface->apply($item, $code, $customer, $items);

		if ($discountData) {
			$this->calculation->addToItems(['discounts'], $discountData);
		}

		return $this;
	}

	public function getGlobalDiscount(DiscountServiceInterface $discountServiceInterface, array $arrayOfItems, ?string $country_code = null): self
	{
		$currentTotal = $this->calculation->getTotal();

		foreach ($arrayOfItems as $index => $item) {
			$this->calculation->setItemsCount($index + 1);
			$discountData = $discountServiceInterface->apply($item, $currentTotal);

			if ($discountData) {
				$this->calculation->addToItems(['discounts'], $discountData);

				$this->totalSingleItem()->calculateTax($item, $country_code);
			}
		}

		return $this;
	}

	public function calculateTax(DiscountableItem $product, ?string $countryCode): self
	{
		$vatRate = $product->getVatRate($countryCode ?? 'HR');

		$this->calculation->calculateItemTax($vatRate);

		return $this;
	}

	public function originalPrice(string $amount): self
	{
		$this->calculation->addToItems('price', $amount);

		return $this;
	}

	public function singleItemQuantity(int $quantity): self
	{
		$this->calculation->addToItems('quantity', $quantity);

		return $this;
	}

	public function totalSingleItem(): self
	{
		$this->calculation->totalSingleItem();

		return $this;
	}

	public function getItemBasicDetails(DiscountableItem $discountableItem, ?string $lang = null): self
	{
		$this->calculation->addToItems('item_id', $discountableItem->id)
						  ->addToItems('item_name', $discountableItem->determineTranslation($lang, 'name'))
						  ->addToItems('item_weight', $discountableItem->weight);


		if($discountableItem instanceof ProductVariant){

			$this->calculation->addToItems('parent_id', $discountableItem->product_id);
		}

		return $this;
	}

	public function calculateShipping(?string $provider = null, ?Address $address): Calculation
	{
		$shipping = null;

		if ($provider && $address) {
			$shipping = $this->shippingCalculationService->decide($this->calculation->getTotalPriceWithDiscountsBeforeShipping(), $this->calculation->getItems(), ShippingServiceFactory::make($provider), $address);
		}

		$this->calculation->shipping($shipping);

		return $this->calculation;
	}

	/**
	 * @return Product|ProductVariant
	 */
	public function getItem(array $item): DiscountableItem
	{
		return isset($item['variant_id']) ? ProductVariant::find($item['variant_id']) : Product::find($item['product_id']);
	}
}
