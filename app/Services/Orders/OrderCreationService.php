<?php

namespace App\Services\Orders;

use App\Models\Order;
use App\Models\Address;
use App\Models\Setting;
use App\Models\Customer;
use Illuminate\Http\Request;

class OrderCreationService
{
	public function create(Request $request, Calculation $calculation, ?Customer $customer, ?string $shippingProvider): Order
	{
		return $this->registeredFlow($request, $calculation, $customer, $shippingProvider);
	}

	public function registeredFlow(Request $request, Calculation $calculation, ?Customer $customer, ?string $shippingProvider): Order
	{
		$orderDetails = $this->getCustomerHardCodedDetails($customer);

		$order = $this->createOrder($calculation, $request, $customer, $orderDetails);

		$this->createShipments($calculation, $order, $shippingProvider);

		$this->createOrderItems($calculation, $order);

		return $order;
	}

	public function createBuyerAddress(Request $request): ?Address
	{
		if ($request->delivery == Order::DELIVERY_SHIPPING) {
			$address = new Address;

			$address->tempAddressCreate($request->delivery_address['name'], $request->delivery_address['street'], $request->delivery_address['city'], $request->delivery_address['zip_code'], $request->delivery_address['country_code']);

			return $address;
		}

		return null;
	}

	public function createOrderItemDiscountsInfo(array $discounts): array
	{
		$discountInfo = [];

		foreach ($discounts as $index => $discount) {
			$discountInfo[$index]['discount_name'] = $discount['discount_name'];
			$discountInfo[$index]['discount_id'] = $discount['discount_id'];
			$discountInfo[$index]['type'] = $discount['type'];
			$discountInfo[$index]['type'] = $discount['type'];
		}

		return $discountInfo;
	}

	public function createOrderDumpStringifiedData(Calculation $calculation): string
	{
		$data = [
			'items' => $calculation->getItems(),
			'total' => $calculation->getTotal()
		];

		return json_encode($data);
	}

	protected function createShipments(Calculation $calculation, Order $order, string $shippingProvider): void
	{
		for ($i = 0; $i < $calculation->getTotal()['shipping']['number_of_packages']; $i++) {
			$order->shipments()->create(['delivery_service' => $shippingProvider]);
		}
	}

	protected function createOrderItems(Calculation $calculation, Order $order): void
	{
		foreach ($calculation->getItems() as $item) {
			if (isset($item['discounts'])) {
				$discountInfo = $this->createOrderItemDiscountsInfo($item['discounts']);
			}

			$itemInfo['id'] = $item['item_id'];
			$itemInfo['name'] = $item['item_name'];

			$order->orderItems()->create([
				'product_id' => isset($item['parent_id']) ? $item['parent_id'] : $item['item_id'],
				'product_variant_id' => isset($item['parent_id']) ? $item['item_id'] : null,
				'price' => $item['price'],
				'tax' => $item['total_tax'],
				'discount_amount' => $item['total_discounts'] ?? 0,
				'total_price' => $item['total_price'],
				'total_price_minus_discounts' => $item['total_price_with_discounts'] ?? 0,
				'quantity' => $item['quantity'],
				'tax_rate' => $item['tax_rate'],
				'discounts_applied' => $discountInfo ?? null,
				'order_item_details' => $itemInfo

			]);
		}
	}

	private function getCustomerHardCodedDetails(?Customer $customer): ?array
	{
		$orderDetails = null;

		if ($customer) {
			$orderDetails['customer_id'] = $customer->id;
			$orderDetails['customer_name'] = $customer->authParent->name;
			$orderDetails['customer_surname'] = $customer->surname;
		}

		return $orderDetails;
	}

	private function createOrder(Calculation $calculation, Request $request, ?Customer $customer, ?array $orderDetails): Order
	{
		return Order::create([
			'customer_id' => $customer?->id,
			'status' => Order::STATUS_PENDING,
			'payment_type' => $request->payment_method,
			'payment_card_provider' => $request->payment_card_provider,
			'number_of_packages' => $calculation->getTotal()['shipping']['number_of_packages'],
			'total_price' => $calculation->getTotal()['order_price'], // ukupna cijena prije popusta
			'total_discounts' => $calculation->getTotal()['order_discounts'], // ukupno popusti
			'total_price_minus_discounts' => $calculation->getTotal()['order_price_minus_discounts'], // ukupna cijena minus popusti
			'tax_total' => $calculation->getTotal()['order_tax'], // iznos poreza u ukupnoj cijeni
			'shipping_price' => $calculation->getTotal()['shipping']['price'], // iznos postarine
			'final_price' => $calculation->getTotal()['final_price_with_shipping_added'], // cijena sa popustima + poštarina,
			'currency' => Setting::getMainCurrency(),
			'order_details' => $orderDetails ?? null,
			'delivery_address' => $request->delivery_address,
			'invoice_address' => $request->invoice_address,
			'order_dump' => $this->createOrderDumpStringifiedData($calculation),
			'guest_mode' => $customer ? false : true,
			'customer_email' => $request->email,
			'store_id' => $request->store_id,
			'reference_number' => Order::createReferenceNumber(),

		]);
	}
}
