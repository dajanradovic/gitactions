<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Store;
use App\Models\Address;
use App\Models\Product;
use App\Models\VatRate;
use App\Models\Category;
use App\Models\Discount;
use App\Models\DiscountItem;
use App\Models\DeliveryPrice;
use App\Settings\GeneralSettings;

class OrderCalculationTest extends TestCase
{
	private ?User $user = null;

	public function setUp(): void
	{
		parent::setUp();
		$this->user = $this->getCustomer();
		$settings = app(GeneralSettings::class);
		$settings->pdv_default = 25;
		$settings->save();

		Discount::where('active', true)->update(['active' => false]);
	}

	public function testValidationShouldFailWrongDeliveryAndProductId(): void
	{
		$product = Product::factory()->for(Category::factory()->create())->create();

		$calculationRequest = [
			'delivery' => 3,
			'items' => [
				[
					'product_id' => 'dssdsadsd',
					'quantity' => 1
				]
			],
		];

		$response = $this->withToken($this->user->token())->postJson(route('api.orders.calculate'), $calculationRequest);

		$response->assertStatus(422);
		$response->assertInvalid([
			'delivery' => 'The selected delivery is invalid.',
			'items.0.product_id' => 'The items.0.product_id must be a valid UUID.'
		]);
	}

	public function testValidationShouldFailCodeNotAvailable(): void
	{
		$product = Product::factory()->for(Category::factory()->create())->create();

		$discount = Discount::factory(['active' => true, 'period_from' => now()->addMonth(), 'type' => Discount::GENERAL_DISCOUNT])->create();

		DiscountItem::factory(['discount_id' => $discount->id, 'item_id' => $product->id])->create();

		$calculationRequest = [
			'delivery' => Order::DELIVERY_SHIPPING,
			'coupon_code' => $discount->code,
			'items' => [
				[
					'product_id' => $product->id,
					'quantity' => 1
				]
			],
		];

		$response = $this->withToken($this->user->token())->postJson(route('api.orders.calculate'), $calculationRequest);

		$response->assertStatus(422);
		$response->assertInvalid([
			'coupon_code' => 'Coupon code cannot be applied, please try with another one',
		]);
	}

	public function testValidationShouldFailCodeDoesntExist(): void
	{
		$product = Product::factory()->for(Category::factory()->create())->create();

		$calculationRequest = [
			'delivery' => Order::DELIVERY_SHIPPING,
			'coupon_code' => 'bla',
			'items' => [
				[
					'product_id' => $product->id,
					'quantity' => 1
				]
			],
		];

		$response = $this->withToken($this->user->token())->postJson(route('api.orders.calculate'), $calculationRequest);

		$response->assertStatus(422);
		$response->assertInvalid([
			'coupon_code' => 'The selected coupon code is invalid.',
		]);
	}

	public function testGuestCalculationOneItemWithoutDiscount(): void
	{
		$product = Product::factory()->for(Category::factory()->create())->create();

		$calculationRequest = [
			'items' => [
				[
					'product_id' => $product->id,
					'quantity' => 1
				]
			],
		];

		$response = $this->postJson(route('api.orders.guest-calculate'), $calculationRequest);

		$response->assertOk()->assertJson([
			'data' => [
				'items' => [
					[
						'item_id' => $product->id,
						'item_name' => $product->name,
						'price' => number_format((float) $product->price, 2),
						'item_weight' => $product->weight,
						'quantity' => 1,
						'total_price' => number_format((float) $product->price, 2)
					]
				],
				'total' => [

					'total_items_quantity' => 1,
					'order_price' => number_format((float) $product->price, 2),
					'order_discounts' => '0.00',
					'order_price_minus_discounts' => number_format((float) $product->price, 2),
					'order_tax' => bcmul($product->price, bcdiv('25', '100', 2), 2),
					'shipping' => [
						'price' => 0,
						'number_of_packages' => 0
					],
					'final_price_with_shipping_added' => $product->price

				]
			]
		]);
	}

	public function testGuestCalculationTwoItemsWithoutDiscount(): void
	{
		$product1 = Product::factory(['price' => '100', 'weight' => 2.31])->for(Category::factory()->create())->create();

		$product2 = Product::factory(['price' => '200'])->for(Category::factory()->create())->create();

		$calculationRequest = [
			'items' => [
				[
					'product_id' => $product1->id,
					'quantity' => 1
				],
				[
					'product_id' => $product2->id,
					'quantity' => 2
				]
			],
		];

		$response = $this->postJson(route('api.orders.guest-calculate'), $calculationRequest);

		$response->assertOk()->assertJson([
			'data' => [
				'items' => [
					[
						'item_id' => $product1->id,
						'item_name' => $product1->name,
						'price' => $product1->price,
						'item_weight' => $product1->weight,
						'quantity' => 1,
						'total_price' => $product1->price
					],
					[
						'item_id' => $product2->id,
						'item_name' => $product2->name,
						'price' => $product2->price,
						'item_weight' => $product2->weight,
						'quantity' => 2,
						'total_price' => bcmul($product2->price, '2', 2)
					]
				],
				'total' => [
					'total_items_quantity' => 3,
					'order_price' => bcadd($product1->price, bcmul($product2->price, '2'), 2),
					'order_discounts' => 0,
					'order_price_minus_discounts' => bcadd($product1->price, bcmul($product2->price, '2'), 2),
					'order_tax' => round(500 * 25 / 100, 2),
					'shipping' => [
						'price' => 0,
						'number_of_packages' => 0
					],
					'final_price_with_shipping_added' => bcadd($product1->price, bcmul($product2->price, '2'), 2)

				]
			]
		]);
	}

	public function testGuestCalculationOneItemWithGeneralDiscount(): void
	{
		$product = Product::factory(['price' => 100])->for(Category::factory()->create())->create();

		$discount = Discount::factory(['active' => true, 'amount' => 20, 'is_percentage' => false, 'period_from' => now()->subDay(), 'code' => null, 'type' => Discount::GENERAL_DISCOUNT])->create();

		DiscountItem::factory(['discount_id' => $discount->id, 'item_id' => $product->id])->create();

		$calculationRequest = [
			'items' => [
				[
					'product_id' => $product->id,
					'quantity' => 1
				]
			],
		];

		$response = $this->postJson(route('api.orders.guest-calculate'), $calculationRequest);

		$response->assertOk()->assertJson([
			'data' => [
				'items' => [
					[
						'item_id' => $product->id,
						'item_name' => $product->name,
						'price' => $product->price,
						'discounts' => [
							[
								'discount_name' => $discount->title,
								'discount_id' => $discount->id,
								'amount' => 20
							]
						],
						'item_weight' => $product->weight,
						'quantity' => 1,
						'total_price' => $product->price,
						'total_discounts' => 20,
						'total_price_with_discounts' => 80,

					]
				],
				'total' => [

					'total_items_quantity' => 1,
					'order_price' => $product->price,
					'order_discounts' => 20,
					'order_price_minus_discounts' => 80,
					'order_tax' => round(80 * 25 / 100, 2),
					'shipping' => [
						'price' => 0,
						'number_of_packages' => 0
					],
					'final_price_with_shipping_added' => bcsub($product->price, '20', 2)

				]
			]
		]);
	}

	public function testGuestCalculationOneItemWithCodeDiscountDiscountShouldReturnZero(): void
	{
		$product = Product::factory(['price' => 100])->for(Category::factory()->create())->create();

		$discount = Discount::factory(['active' => true, 'amount' => 20, 'is_percentage' => false, 'period_from' => now()->subDay(), 'code' => '232', 'type' => Discount::GENERAL_DISCOUNT])->create();

		DiscountItem::factory(['discount_id' => $discount->id, 'item_id' => $product->id])->create();

		$calculationRequest = [
			'items' => [
				[
					'product_id' => $product->id,
					'quantity' => 1
				]
			],
		];

		$response = $this->postJson(route('api.orders.guest-calculate'), $calculationRequest);

		$response->assertOk()->assertJson([
			'data' => [
				'items' => [
					[
						'item_id' => $product->id,
						'item_name' => $product->name,
						'price' => $product->price,
						'item_weight' => $product->weight,
						'quantity' => 1,
						'total_price' => $product->price
					]
				],
				'total' => [

					'total_items_quantity' => 1,
					'order_price' => $product->price,
					'order_discounts' => 0,
					'order_price_minus_discounts' => $product->price,
					'order_tax' => bcmul($product->price, bcdiv('25', '100', 2), 2),
					'shipping' => [
						'price' => 0,
						'number_of_packages' => 0
					],
					'final_price_with_shipping_added' => $product->price

				]
			]
		]);
	}

	public function testGuestCalculationTwoItemsWithTwoDifferentGeneralDiscountsOneValueOnePercentage(): void
	{
		$product1 = Product::factory(['price' => 100])->for(Category::factory()->create())->create();

		$product2 = Product::factory(['price' => 200])->for(Category::factory()->create())->create();

		$discount1 = Discount::factory(['active' => true, 'amount' => 20, 'is_percentage' => false, 'period_from' => now()->subDay(), 'code' => null, 'type' => Discount::GENERAL_DISCOUNT])->create();

		$discount2 = Discount::factory(['active' => true, 'amount' => 20, 'is_percentage' => true, 'period_from' => now()->subDay(), 'code' => null, 'type' => Discount::GENERAL_DISCOUNT])->create();

		DiscountItem::factory(['discount_id' => $discount1->id, 'item_id' => $product1->id])->create();

		DiscountItem::factory(['discount_id' => $discount2->id, 'item_id' => $product2->id])->create();

		$calculationRequest = [
			'items' => [
				[
					'product_id' => $product1->id,
					'quantity' => 1
				],
				[
					'product_id' => $product2->id,
					'quantity' => 2
				]
			],
		];

		$response = $this->postJson(route('api.orders.guest-calculate'), $calculationRequest);

		$response->assertOk()->assertJson([
			'data' => [
				'items' => [
					[
						'item_id' => $product1->id,
						'item_name' => $product1->name,
						'price' => $product1->price,
						'discounts' => [
							[
								'discount_name' => $discount1->title,
								'discount_id' => $discount1->id,
								'amount' => 20
							]
						],
						'item_weight' => $product1->weight,
						'quantity' => 1,
						'total_price' => $product1->price,
						'total_discounts' => 20,
						'total_price_with_discounts' => 80,

					],
					[
						'item_id' => $product2->id,
						'item_name' => $product2->name,
						'price' => $product2->price,
						'discounts' => [
							[
								'discount_name' => $discount2->title,
								'discount_id' => $discount2->id,
								'amount' => 40
							]
						],
						'item_weight' => $product2->weight,
						'quantity' => 2,
						'total_price' => bcmul($product2->price, '2', 2),
						'total_discounts' => 80,
						'total_price_with_discounts' => 400 - 80,

					]
				],
				'total' => [

					'total_items_quantity' => 3,
					'order_price' => 500,
					'order_discounts' => 100,
					'order_price_minus_discounts' => 400,
					'order_tax' => round(400 * 25 / 100, 2),
					'shipping' => [
						'price' => 0,
						'number_of_packages' => 0
					],
					'final_price_with_shipping_added' => 400

				]
			]
		]);
	}

	public function testLoggedInCalculationOneItemWithoutDiscountWithPickupInStore(): void
	{
		$product = Product::factory(['price' => 300])->for(Category::factory()->create())->create();

		$store = Store::factory()->create();

		$calculationRequest = [
			'delivery' => 2,
			'payment_method' => 1,
			'store_id' => $store->id,
			'email' => $this->faker->email(),
			'items' => [
				[
					'product_id' => $product->id,
					'quantity' => 1
				]
			],
		];

		$response = $this->withToken($this->user->token())->postJson(route('api.orders.calculate'), $calculationRequest);

		$response->assertOk()->assertJson([
			'data' => [
				'items' => [
					[
						'item_id' => $product->id,
						'item_name' => $product->name,
						'price' => $product->price,
						'item_weight' => $product->weight,
						'quantity' => 1,
						'tax_rate' => '25 %',
						'total_tax' => 75,
						'total_price' => $product->price
					]
				],
				'total' => [

					'total_items_quantity' => 1,
					'order_price' => $product->price,
					'order_discounts' => 0,
					'order_price_minus_discounts' => $product->price,
					'order_tax' => 75,
					'shipping' => [
						'price' => 0,
						'number_of_packages' => 0
					],
					'final_price_with_shipping_added' => $product->price

				]
			]
		]);
	}

	public function testLoggedInCalculationOneItemWithoutDiscountWithPayOnDelivery(): void
	{
		$product = Product::factory(['price' => 300])->for(Category::factory()->create())->create();

		$store = Store::factory()->create();

		$calculationRequest = [
			'delivery' => 2,
			'store_id' => $store->id,
			'payment_method' => 1,
			'email' => $this->faker->email(),
			'items' => [
				[
					'product_id' => $product->id,
					'quantity' => 1
				]
			],
		];

		$response = $this->withToken($this->user->token())->postJson(route('api.orders.calculate'), $calculationRequest);

		$response->assertOk()->assertJson([
			'data' => [
				'items' => [
					[
						'item_id' => $product->id,
						'item_name' => $product->name,
						'price' => $product->price,
						'item_weight' => $product->weight,
						'quantity' => 1,
						'tax_rate' => '25 %',
						'total_tax' => 75,
						'total_price' => $product->price
					]
				],
				'total' => [

					'total_items_quantity' => 1,
					'order_price' => $product->price,
					'order_discounts' => 0,
					'order_price_minus_discounts' => $product->price,
					'order_tax' => 75,
					'shipping' => [
						'price' => 0,
						'number_of_packages' => 0
					],
					'final_price_with_shipping_added' => $product->price

				]
			]
		]);
	}

	public function testLoggedInCalculationTwoItemsWithDifferemtTaxRatesWithoutDiscountWithPayOnDelivery(): void
	{
		DeliveryPrice::factory(['delivery_service' => 'DPD'])->create();

		$categoryTaxRate10 = Category::factory()->create();

		$categoryTaxRate25 = Category::factory()->create();

		VatRate::factory(['country_code' => 'HR', 'amount' => 10, 'category_id' => $categoryTaxRate10->id])->create();

		VatRate::factory(['country_code' => 'HR', 'amount' => 25, 'category_id' => $categoryTaxRate25->id])->create();

		$productTaxRate10 = Product::factory(['price' => 100, 'weight' => 25])->for($categoryTaxRate10)->create();

		$productTaxRate25 = Product::factory(['price' => 300, 'weight' => 10])->for($categoryTaxRate25)->create();

		Address::factory(['type' => 1, 'country_code' => 'HR'])->create(['customer_id' => $this->user->user->id]);

		$calculationRequest = [
			'delivery' => 1,
			'payment_method' => 2,
			'shipping_provider' => 'DPD',
			'email' => $this->faker->email(),
			'items' => [
				[
					'product_id' => $productTaxRate10->id,
					'quantity' => 1
				],
				[
					'product_id' => $productTaxRate25->id,
					'quantity' => 1
				]
			],
			'delivery_address' => [
				'name' => $this->faker->name(),
				'street' => 'Rudarska',
				'city' => 'Labin',
				'zip_code' => '52220',
				'country_code' => 'HR',
				'phone' => 9342343324324,
			]
		];

		$response = $this->withToken($this->user->token())->postJson(route('api.orders.calculate'), $calculationRequest);

		$response->assertOk()->assertJson([
			'data' => [
				'items' => [
					[
						'item_id' => $productTaxRate10->id,
						'item_name' => $productTaxRate10->name,
						'item_weight' => $productTaxRate10->weight,
						'price' => number_format((float) $productTaxRate10->price, 2),
						'quantity' => 1,
						'total_price' => number_format((float) $productTaxRate10->price, 2),
						'tax_rate' => '10 %',
						'total_tax' => number_format(10, 2)
					],
					[
						'item_id' => $productTaxRate25->id,
						'item_name' => $productTaxRate25->name,
						'item_weight' => $productTaxRate25->weight,
						'price' => number_format((float) $productTaxRate25->price, 2),
						'quantity' => 1,
						'total_price' => number_format((float) $productTaxRate25->price, 2),
						'tax_rate' => '25 %',
						'total_tax' => number_format(75, 2)
					]
				],
				'total' => [

					'total_items_quantity' => 2,
					'order_price' => number_format(100 + 300, 2),
					'order_discounts' => number_format(0, 2),
					'order_price_minus_discounts' => number_format(100 + 300, 2),
					'order_tax' => number_format(85, 2),
					'shipping' => [
						'price' => 0,
						'number_of_packages' => 2
					],
					'final_price_with_shipping_added' => number_format(400, 2)

				]
			]
		]);
	}

	public function testValidationCantUsePointsDiscountIfNotLoggedin(): void
	{
		$product = Product::factory(['price' => 300])->for(Category::factory()->create())->create();

		$calculationRequest = [
			'delivery' => 2,
			'payment_method' => 2,
			'points' => 34,
			'items' => [
				[
					'product_id' => $product->id,
					'quantity' => 1
				]
			],
		];

		$response = $this->postJson(route('api.orders.calculate'), $calculationRequest);

		$response->assertStatus(422);
		$response->assertInvalid(['points' => 'The points field is prohibited.']);
	}

	public function testValidationCantUseDeliveryMethodIfAddressIsNotProvided(): void
	{
		$product = Product::factory(['price' => 300])->for(Category::factory()->create())->create();

		$calculationRequest = [
			'delivery' => 1,
			'shipping_provider' => 'DPD',
			'payment_method' => 2,
			'items' => [
				[
					'product_id' => $product->id,
					'quantity' => 1
				]
			],
		];

		$response = $this->postJson(route('api.orders.calculate'), $calculationRequest);

		$response->assertStatus(422);
		$response->assertInvalid(['delivery_address' => 'The delivery address field is required.']);
	}
}
