<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\Discount;
use App\Traits\Countries;
use App\Models\DiscountItem;
use App\Mail\ProductInquiryMail;
use App\Settings\GeneralSettings;
use Illuminate\Support\Facades\Mail;

class ProductTest extends TestCase
{
	use Countries;

	public function setUp(): void
	{
		parent::setUp();
		$settings = app(GeneralSettings::class);
		$settings->pdv_default = 25;
		$settings->save();
	}

	public function testCreateProductVariants(): void
	{
		$user = $this->getUser();

		$product = Product::factory()->create();
		$category = Category::factory()->create();

		$data = array_merge(['variants' => ['pola kile'],
			'variants_price' => [400],
			'variants_measure' => [0.25],
			'variants_en' => ['english'],
			'variant_ids' => [null],
			'variants_weight' => [11],
			'category_id' => $category->id], $product->toArray());

		$response = $this->actingAs($user)->post('products/' . $product['id'], $data);

		$this->assertDatabaseHas('product_variants', [
			'name' => 'pola kile',
			'price' => 400,
			'measure' => 0.25,
			'product_id' => $product['id']
		]);

		$this->assertCount(1, $product->variants);
		$response->assertStatus(302);
	}

	public function testCreateProductVariantsValidationFail(): void
	{
		$user = $this->getUser();

		$product = Product::factory()->create();

		$data = array_merge(['variants' => ['pola kile'],
			'variants_price' => [400],
			'variants_measure' => [0.25],
			'variants_en' => ['english'],
		], $product->toArray());

		$response = $this->actingAs($user)->post('products/' . $product['id'], $data);
		$response->assertSessionHasErrors();
		$response->assertStatus(302);
	}

	public function testApiGetSingleProduct(): void
	{
		$product = Product::factory()->for(Category::factory()->create())->create();

		$response = $this->getJson(route('api.products.single', ['product' => $product->id]));
		$response->assertStatus(200);
	}

	public function testApiGetSingleProductHasPiktogramNovo(): void
	{
		$product = Product::factory()->for(Category::factory()->create())->create();

		$response = $this->getJson(route('api.products.single', ['product' => $product->id]));
		$response->assertStatus(200)
				 ->assertJson(['data' => [
				 	'piktograms' => [Product::DYNAMIC_PIKTOGRAM_NOVO]
				 ]

				 ]);
	}

	public function testApiGetSingleProductDoesntHavePiktogramNovo(): void
	{
		$product = Product::factory()->for(Category::factory()->create())->create(['created_at' => now()->subMonths(2)]);

		$response = $this->getJson(route('api.products.single', ['product' => $product->id]));
		$response->assertStatus(200)
				 ->assertJsonMissing(['data' => [
				 	'piktograms' => [Product::DYNAMIC_PIKTOGRAM_NOVO]
				 ]

				 ]);
	}

	public function testApiProductInquiryRouteAndMailSending(): void
	{
		Mail::fake();

		$product = Product::factory()->create();

		$data = [
			'id' => $product->id,
			'message' => 'Hello',
			'email' => $this->faker->email(),
			'type' => Product::VINE_HARVEST_INQUIRY
		];

		$response = $this->postJson(route('api.products.inquiry', $data));

		Mail::assertQueued(ProductInquiryMail::class);

		$response->assertStatus(200)
				 ->assertJson(['data' => [
				 	'success' => true
				 ]
				 ]);
	}

	public function testUserLikes(): void
	{
		$user = $this->getCustomer();

		$product = Product::factory()->for(Category::factory()->create())->create();

		$response = $this->actingAs($user)->postJson(route('api.products.like', $product->id));

		$response->assertOk()->assertJson([
			'data' => [
				'user_liked' => true
			]
		]);

		$response = $this->actingAs($user)->postJson(route('api.products.like', $product->id));

		$response->assertOk()->assertJson([
			'data' => [
				'user_liked' => false
			]
		]);
	}

	public function testGetProductVatRateWhenVatRateDoesntExits(): void
	{
		$category = Category::factory()->create();

		$product = Product::factory()->for($category)->create();

		$this->assertEquals(setting('pdv_default'), $product->getVatRate());
	}

	public function testGetProductVatRate(): void
	{
		$category = Category::factory()->create();

		foreach (self::getCountries() as $country) {
			$category->vatRates()->create([
				'country_code' => $country,
				'amount' => $country == self::$homeCountry ? 20 : 0
			]);
		}

		$product = Product::factory()->for($category)->create();

		$this->assertEquals(20, $product->getVatRate());
	}

	public function testGetCurrentlyApplicableDiscount(): void
	{
		$product = Product::factory()->for(Category::factory()->create())->create();

		$discount = Discount::factory(['active' => true, 'amount' => 20, 'is_percentage' => false, 'period_from' => now()->subDay(), 'code' => null, 'type' => Discount::GENERAL_DISCOUNT])->create();

		DiscountItem::factory(['discount_id' => $discount->id, 'item_id' => $product->id])->create();

		$this->assertEquals($discount->id, $product->getCurrentlyApplicableDiscount()->id);
	}
}
