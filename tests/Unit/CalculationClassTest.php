<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Orders\Calculation;

class CalculationClassTest extends TestCase
{
	public array $data = [];

	public function setUp(): void
	{
		parent::setUp();

		$this->data = [
			['d78da870-759a-4936-b0f2-c6dc35aeee87', 'auto', 10, 50, 1],
			['4c53f334-96b6-4e20-be5d-ec841f7a9830', 'gitara', 20, 100, 1],
			['ca455dad-2ee9-4e1a-b918-a522d628b81c', 'kisobran', 5, 100, 4],
			['625d565a-6ea8-4f0f-a87b-610bb1170a70', 'sir', 23, 200, 2],

		];
	}

	public function testAddToItems(): void
	{
		$calculation = new Calculation;

		foreach ($this->data as $item) {
			$calculation->addToItems('item_id', $item[0])->addToItems('item_name', $item[1])->addToItems('item_weight', $item[2]);
			$calculation->addToItems('price', $item[3]);
			$calculation->addToItems('quantity', $item[4]);

			$calculation->increaseItemsCount();
		}

		$expectedOutput = [
			1 => [
				'item_id' => 'd78da870-759a-4936-b0f2-c6dc35aeee87',
				'item_name' => 'auto',
				'item_weight' => 10,
				'price' => 50,
				'quantity' => 1,
			],
			2 => [
				'item_id' => '4c53f334-96b6-4e20-be5d-ec841f7a9830',
				'item_name' => 'gitara',
				'item_weight' => 20,
				'price' => 100,
				'quantity' => 1,
			],
			3 => [
				'item_id' => 'ca455dad-2ee9-4e1a-b918-a522d628b81c',
				'item_name' => 'kisobran',
				'item_weight' => 5,
				'price' => 100,
				'quantity' => 4,
			],
			4 => [
				'item_id' => '625d565a-6ea8-4f0f-a87b-610bb1170a70',
				'item_name' => 'sir',
				'item_weight' => 23,
				'price' => 200,
				'quantity' => 2,
			]
		];

		$this->assertEquals($expectedOutput, $calculation->getItems());
	}

	public function testTotalSingleItem(): void
	{
		$calculation = new Calculation;

		foreach ($this->data as $item) {
			$calculation->addToItems('item_id', $item[0])->addToItems('item_name', $item[1])->addToItems('item_weight', $item[2]);
			$calculation->addToItems('price', $item[3]);
			$calculation->addToItems('quantity', $item[4]);

			$calculation->totalSingleItem();
			$calculation->increaseItemsCount();
		}

		$expectedOutput = [
			1 => [
				'item_id' => 'd78da870-759a-4936-b0f2-c6dc35aeee87',
				'item_name' => 'auto',
				'item_weight' => 10,
				'price' => 50,
				'quantity' => 1,
				'total_price' => 50

			],
			2 => [
				'item_id' => '4c53f334-96b6-4e20-be5d-ec841f7a9830',
				'item_name' => 'gitara',
				'item_weight' => 20,
				'price' => 100,
				'quantity' => 1,
				'total_price' => 100

			],
			3 => [
				'item_id' => 'ca455dad-2ee9-4e1a-b918-a522d628b81c',
				'item_name' => 'kisobran',
				'item_weight' => 5,
				'price' => 100,
				'quantity' => 4,
				'total_price' => 400

			],
			4 => [
				'item_id' => '625d565a-6ea8-4f0f-a87b-610bb1170a70',
				'item_name' => 'sir',
				'item_weight' => 23,
				'price' => 200,
				'quantity' => 2,
				'total_price' => 400
			]
		];

		$this->assertEquals($expectedOutput, $calculation->getItems());
	}

	public function testCalculateItemTax(): void
	{
		$calculation = new Calculation;

		foreach ($this->data as $index => $item) {
			$calculation->addToItems('item_id', $item[0])->addToItems('item_name', $item[1])->addToItems('item_weight', $item[2]);
			$calculation->addToItems('price', $item[3]);
			$calculation->addToItems('quantity', $item[4]);

			$calculation->totalSingleItem();

			if ($index == 3) {
				$calculation->calculateItemTax(20);
			} else {
				$calculation->calculateItemTax(10);
			}

			$calculation->increaseItemsCount();
		}

		$expectedOutput = [
			1 => [
				'item_id' => 'd78da870-759a-4936-b0f2-c6dc35aeee87',
				'item_name' => 'auto',
				'item_weight' => 10,
				'price' => 50,
				'quantity' => 1,
				'total_price' => 50,
				'tax_rate' => '10 %',
				'total_tax' => 5.0

			],
			2 => [
				'item_id' => '4c53f334-96b6-4e20-be5d-ec841f7a9830',
				'item_name' => 'gitara',
				'item_weight' => 20,
				'price' => 100,
				'quantity' => 1,
				'total_price' => 100,
				'tax_rate' => '10 %',
				'total_tax' => 10.0

			],
			3 => [
				'item_id' => 'ca455dad-2ee9-4e1a-b918-a522d628b81c',
				'item_name' => 'kisobran',
				'item_weight' => 5,
				'price' => 100,
				'quantity' => 4,
				'total_price' => 400,
				'tax_rate' => '10 %',
				'total_tax' => 40.0

			],
			4 => [
				'item_id' => '625d565a-6ea8-4f0f-a87b-610bb1170a70',
				'item_name' => 'sir',
				'item_weight' => 23,
				'price' => 200,
				'quantity' => 2,
				'total_price' => 400,
				'tax_rate' => '20 %',
				'total_tax' => 80.0
			]
		];

		$this->assertEquals($expectedOutput, $calculation->getItems());
	}

	public function testAddToItemsWithDiscounts(): void
	{
		$calculation = new Calculation;

		$data = [
			['d78da870-759a-4936-b0f2-c6dc35aeee87', 'auto', 10, 50, 1],
			['4c53f334-96b6-4e20-be5d-ec841f7a9830', 'gitara', 20, 100, 1],
			['ca455dad-2ee9-4e1a-b918-a522d628b81c', 'kisobran', 5, 100, 4],
			['625d565a-6ea8-4f0f-a87b-610bb1170a70', 'sir', 23, 200, 2],

		];

		$discountObject = ['discount_name' => 'testni discount',
			'discount_id' => '625d565a-6za8-4f0f-a87b-610bb1170a74',
			'type' => 'value',
			'amount' => 10
		];

		foreach ($data as $item) {
			$calculation->addToItems('item_id', $item[0])->addToItems('item_name', $item[1])->addToItems('item_weight', $item[2]);
			$calculation->addToItems('price', $item[3]);
			$calculation->addToItems('quantity', $item[4]);
			$calculation->addToItems(['discounts'], $discountObject);

			$calculation->increaseItemsCount();
		}

		$expectedOutput = [
			1 => [
				'item_id' => 'd78da870-759a-4936-b0f2-c6dc35aeee87',
				'item_name' => 'auto',
				'item_weight' => 10,
				'price' => 50,
				'quantity' => 1,
				'discounts' => [
					[
						'discount_name' => 'testni discount',
						'discount_id' => '625d565a-6za8-4f0f-a87b-610bb1170a74',
						'type' => 'value',
						'amount' => 10
					],
				],
			],
			2 => [
				'item_id' => '4c53f334-96b6-4e20-be5d-ec841f7a9830',
				'item_name' => 'gitara',
				'item_weight' => 20,
				'price' => 100,
				'quantity' => 1,
				'discounts' => [
					[
						'discount_name' => 'testni discount',
						'discount_id' => '625d565a-6za8-4f0f-a87b-610bb1170a74',
						'type' => 'value',
						'amount' => 10
					],
				],
			],
			3 => [
				'item_id' => 'ca455dad-2ee9-4e1a-b918-a522d628b81c',
				'item_name' => 'kisobran',
				'item_weight' => 5,
				'price' => 100,
				'quantity' => 4,
				'discounts' => [
					[
						'discount_name' => 'testni discount',
						'discount_id' => '625d565a-6za8-4f0f-a87b-610bb1170a74',
						'type' => 'value',
						'amount' => 10
					],
				],
			],
			4 => [
				'item_id' => '625d565a-6ea8-4f0f-a87b-610bb1170a70',
				'item_name' => 'sir',
				'item_weight' => 23,
				'price' => 200,
				'quantity' => 2,
				'discounts' => [
					[
						'discount_name' => 'testni discount',
						'discount_id' => '625d565a-6za8-4f0f-a87b-610bb1170a74',
						'type' => 'value',
						'amount' => 10
					],
				]
			]
		];

		$this->assertEquals($expectedOutput, $calculation->getItems());
	}

	public function testTotalSingleItemWithDiscounts(): void
	{
		$calculation = new Calculation;

		$data = [
			['d78da870-759a-4936-b0f2-c6dc35aeee87', 'auto', 10, 50, 1],
			['4c53f334-96b6-4e20-be5d-ec841f7a9830', 'gitara', 20, 100, 1],
			['ca455dad-2ee9-4e1a-b918-a522d628b81c', 'kisobran', 5, 100, 4],
			['625d565a-6ea8-4f0f-a87b-610bb1170a70', 'sir', 23, 200, 2],

		];

		$discountObject = ['discount_name' => 'testni discount',
			'discount_id' => '625d565a-6za8-4f0f-a87b-610bb1170a74',
			'type' => 'value',
			'amount' => 10
		];

		foreach ($data as $item) {
			$calculation->addToItems('item_id', $item[0])->addToItems('item_name', $item[1])->addToItems('item_weight', $item[2]);
			$calculation->addToItems('price', $item[3]);
			$calculation->addToItems('quantity', $item[4]);
			$calculation->addToItems(['discounts'], $discountObject);

			$calculation->totalSingleItem();

			$calculation->increaseItemsCount();
		}

		$expectedOutput = [
			1 => [
				'item_id' => 'd78da870-759a-4936-b0f2-c6dc35aeee87',
				'item_name' => 'auto',
				'item_weight' => 10,
				'price' => 50,
				'quantity' => 1,
				'total_price' => 50.0,
				'total_discounts' => 10.0,
				'total_price_with_discounts' => 40.0,
				'discounts' => [
					[
						'discount_name' => 'testni discount',
						'discount_id' => '625d565a-6za8-4f0f-a87b-610bb1170a74',
						'type' => 'value',
						'amount' => 10
					],
				],
			],
			2 => [
				'item_id' => '4c53f334-96b6-4e20-be5d-ec841f7a9830',
				'item_name' => 'gitara',
				'item_weight' => 20,
				'price' => 100,
				'quantity' => 1,
				'total_price' => 100.0,
				'total_discounts' => 10.0,
				'total_price_with_discounts' => 90.0,
				'discounts' => [
					[
						'discount_name' => 'testni discount',
						'discount_id' => '625d565a-6za8-4f0f-a87b-610bb1170a74',
						'type' => 'value',
						'amount' => 10
					],
				],
			],
			3 => [
				'item_id' => 'ca455dad-2ee9-4e1a-b918-a522d628b81c',
				'item_name' => 'kisobran',
				'item_weight' => 5,
				'price' => 100,
				'quantity' => 4,
				'total_price' => 400.0,
				'total_discounts' => 40.0,
				'total_price_with_discounts' => 360.0,
				'discounts' => [
					[
						'discount_name' => 'testni discount',
						'discount_id' => '625d565a-6za8-4f0f-a87b-610bb1170a74',
						'type' => 'value',
						'amount' => 10
					],
				],
			],
			4 => [
				'item_id' => '625d565a-6ea8-4f0f-a87b-610bb1170a70',
				'item_name' => 'sir',
				'item_weight' => 23,
				'price' => 200,
				'quantity' => 2,
				'total_price' => 400.0,
				'total_discounts' => 20.0,
				'total_price_with_discounts' => 380.0,
				'discounts' => [
					[
						'discount_name' => 'testni discount',
						'discount_id' => '625d565a-6za8-4f0f-a87b-610bb1170a74',
						'type' => 'value',
						'amount' => 10
					],
				]
			]
		];

		$this->assertEquals($expectedOutput, $calculation->getItems());
	}

	public function testTotalSingleItemCompeteWithDiscountsAndTax(): void
	{
		$calculation = new Calculation;

		$data = [
			['d78da870-759a-4936-b0f2-c6dc35aeee87', 'auto', 10, 50, 1],
			['4c53f334-96b6-4e20-be5d-ec841f7a9830', 'gitara', 20, 100, 1],
			['ca455dad-2ee9-4e1a-b918-a522d628b81c', 'kisobran', 5, 100, 4],
			['625d565a-6ea8-4f0f-a87b-610bb1170a70', 'sir', 23, 200, 2],

		];

		$discountObject = ['discount_name' => 'testni discount',
			'discount_id' => '625d565a-6za8-4f0f-a87b-610bb1170a74',
			'type' => 'value',
			'amount' => 10
		];

		foreach ($data as $item) {
			$calculation->addToItems('item_id', $item[0])->addToItems('item_name', $item[1])->addToItems('item_weight', $item[2]);
			$calculation->addToItems('price', $item[3]);
			$calculation->addToItems('quantity', $item[4]);
			$calculation->addToItems(['discounts'], $discountObject);

			$calculation->totalSingleItem();
			$calculation->calculateItemTax(10);
			$calculation->increaseItemsCount();
		}

		$expectedOutput = [
			1 => [
				'item_id' => 'd78da870-759a-4936-b0f2-c6dc35aeee87',
				'item_name' => 'auto',
				'item_weight' => 10,
				'price' => 50,
				'quantity' => 1,
				'total_price' => 50.0,
				'total_discounts' => 10.0,
				'total_price_with_discounts' => 40.0,
				'tax_rate' => '10 %',
				'total_tax' => 4.0,
				'discounts' => [
					[
						'discount_name' => 'testni discount',
						'discount_id' => '625d565a-6za8-4f0f-a87b-610bb1170a74',
						'type' => 'value',
						'amount' => 10
					],
				],
			],
			2 => [
				'item_id' => '4c53f334-96b6-4e20-be5d-ec841f7a9830',
				'item_name' => 'gitara',
				'item_weight' => 20,
				'price' => 100,
				'quantity' => 1,
				'total_price' => 100.0,
				'total_discounts' => 10.0,
				'total_price_with_discounts' => 90.0,
				'tax_rate' => '10 %',
				'total_tax' => 9.0,
				'discounts' => [
					[
						'discount_name' => 'testni discount',
						'discount_id' => '625d565a-6za8-4f0f-a87b-610bb1170a74',
						'type' => 'value',
						'amount' => 10
					],
				],
			],
			3 => [
				'item_id' => 'ca455dad-2ee9-4e1a-b918-a522d628b81c',
				'item_name' => 'kisobran',
				'item_weight' => 5,
				'price' => 100,
				'quantity' => 4,
				'total_price' => 400.0,
				'total_discounts' => 40.0,
				'total_price_with_discounts' => 360.0,
				'tax_rate' => '10 %',
				'total_tax' => 36.0,
				'discounts' => [
					[
						'discount_name' => 'testni discount',
						'discount_id' => '625d565a-6za8-4f0f-a87b-610bb1170a74',
						'type' => 'value',
						'amount' => 10
					],
				],
			],
			4 => [
				'item_id' => '625d565a-6ea8-4f0f-a87b-610bb1170a70',
				'item_name' => 'sir',
				'item_weight' => 23,
				'price' => 200,
				'quantity' => 2,
				'total_price' => 400.0,
				'total_discounts' => 20.0,
				'total_price_with_discounts' => 380.0,
				'tax_rate' => '10 %',
				'total_tax' => 38.0,
				'discounts' => [
					[
						'discount_name' => 'testni discount',
						'discount_id' => '625d565a-6za8-4f0f-a87b-610bb1170a74',
						'type' => 'value',
						'amount' => 10
					],
				]
			]
		];

		$this->assertEquals($expectedOutput, $calculation->getItems());
	}

	public function testTotalSingleItemCompeteWithDiscountsAndTaxButDiscountsAppliedNotOnAllItems(): void
	{
		$calculation = new Calculation;

		$data = [
			['d78da870-759a-4936-b0f2-c6dc35aeee87', 'auto', 10, 50, 1],
			['4c53f334-96b6-4e20-be5d-ec841f7a9830', 'gitara', 20, 100, 1],

		];

		$discountObject = ['discount_name' => 'testni discount',
			'discount_id' => '625d565a-6za8-4f0f-a87b-610bb1170a74',
			'type' => 'value',
			'amount' => 10
		];

		foreach ($data as $index => $item) {
			$calculation->addToItems('item_id', $item[0])->addToItems('item_name', $item[1])->addToItems('item_weight', $item[2]);
			$calculation->addToItems('price', $item[3]);
			$calculation->addToItems('quantity', $item[4]);

			if ($index == 1) {
				$calculation->addToItems(['discounts'], $discountObject);
			}

			$calculation->totalSingleItem();
			$calculation->calculateItemTax(10);
			$calculation->increaseItemsCount();
		}

		$expectedOutput = [
			1 => [
				'item_id' => 'd78da870-759a-4936-b0f2-c6dc35aeee87',
				'item_name' => 'auto',
				'item_weight' => 10,
				'price' => 50,
				'quantity' => 1,
				'total_price' => 50.0,
				'tax_rate' => '10 %',
				'total_tax' => 5.0,

			],
			2 => [
				'item_id' => '4c53f334-96b6-4e20-be5d-ec841f7a9830',
				'item_name' => 'gitara',
				'item_weight' => 20,
				'price' => 100,
				'quantity' => 1,
				'total_price' => 100.0,
				'total_discounts' => 10.0,
				'total_price_with_discounts' => 90.0,
				'tax_rate' => '10 %',
				'total_tax' => 9.0,
				'discounts' => [
					[
						'discount_name' => 'testni discount',
						'discount_id' => '625d565a-6za8-4f0f-a87b-610bb1170a74',
						'type' => 'value',
						'amount' => 10
					],
				],
			],
		];

		$this->assertEquals($expectedOutput, $calculation->getItems());
	}

	public function testOrderTotalDiscountsAndTaxButDiscountsAppliedNotOnAllItemsAndNoShippingApplied(): void
	{
		$calculation = new Calculation;

		$data = [
			['d78da870-759a-4936-b0f2-c6dc35aeee87', 'auto', 10, 50, 1],
			['4c53f334-96b6-4e20-be5d-ec841f7a9830', 'gitara', 20, 100, 1],

		];

		$discountObject = ['discount_name' => 'testni discount',
			'discount_id' => '625d565a-6za8-4f0f-a87b-610bb1170a74',
			'type' => 'value',
			'amount' => 10
		];

		foreach ($data as $index => $item) {
			$calculation->addToItems('item_id', $item[0])->addToItems('item_name', $item[1])->addToItems('item_weight', $item[2]);
			$calculation->addToItems('price', $item[3]);
			$calculation->addToItems('quantity', $item[4]);

			if ($index == 1) {
				$calculation->addToItems(['discounts'], $discountObject);
			}

			$calculation->totalSingleItem();
			$calculation->calculateItemTax(10);
			$calculation->increaseItemsCount();
		}

		$calculation->total();

		$expectedOutput = [

			'total_items_quantity' => 2,
			'order_price' => 150.0,
			'order_discounts' => 10.0,
			'order_price_minus_discounts' => 140.0,
			'order_tax' => 14.0,
			'shipping' => [
				'number_of_packages' => 0,
				'price' => 0
			],
			'final_price_with_shipping_added' => 140.0

		];

		$this->assertEquals($expectedOutput, $calculation->getTotal());
	}

	public function testOrderTotalDiscountsAndTaxButDiscountsAppliedNotOnAllItemsAndShippingApplied(): void
	{
		$calculation = new Calculation;

		$data = [
			['d78da870-759a-4936-b0f2-c6dc35aeee87', 'auto', 10, 50, 1],
			['4c53f334-96b6-4e20-be5d-ec841f7a9830', 'gitara', 20, 100, 1],

		];

		$discountObject = ['discount_name' => 'testni discount',
			'discount_id' => '625d565a-6za8-4f0f-a87b-610bb1170a74',
			'type' => 'value',
			'amount' => 10
		];

		foreach ($data as $index => $item) {
			$calculation->addToItems('item_id', $item[0])->addToItems('item_name', $item[1])->addToItems('item_weight', $item[2]);
			$calculation->addToItems('price', $item[3]);
			$calculation->addToItems('quantity', $item[4]);

			if ($index == 1) {
				$calculation->addToItems(['discounts'], $discountObject);
			}

			$calculation->totalSingleItem();
			$calculation->calculateItemTax(10);
			$calculation->increaseItemsCount();
		}
		$calculation->shipping(['number_of_packages' => 2, 'price' => 40]);
		$calculation->total();

		$expectedOutput = [

			'total_items_quantity' => 2,
			'order_price' => 150.0,
			'order_discounts' => 10.0,
			'order_price_minus_discounts' => 140.0,
			'order_tax' => 14.0,
			'shipping' => [
				'number_of_packages' => 2,
				'price' => 40
			],
			'final_price_with_shipping_added' => 180.0

		];

		$this->assertEquals($expectedOutput, $calculation->getTotal());
	}
}
