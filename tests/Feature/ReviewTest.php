<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Review;
use App\Models\Product;

class ReviewTest extends TestCase
{
	private ?User $user = null;

	public function setUp(): void
	{
		parent::setUp();
		$this->user = $this->getCustomer();
	}

	public function testCreateGeneralReview(): void
	{
		$review = Review::factory()->general()->make()->toArray();

		$response = $this->withToken($this->user->token())->postJson(route('api.reviews.reviews'), $review);

		$response->assertJsonPath('data.body', $review['description'])
				 ->assertJsonPath('data.author.id', $this->user->user->id);
		$response->assertStatus(201);
	}

	public function testCreateProductReview(): void
	{
		$review = Review::factory()->product()->make()->toArray();

		$product = Product::factory()->create();

		$response = $this->withToken($this->user->token())->postJson(route('api.products.review', ['id' => $product->id]), $review);

		$response->assertJsonPath('data.rating', $review['rating'])
				 ->assertJsonPath('data.author.id', $this->user->user->id);
		$response->assertStatus(201);
	}

	public function testCantCreateGeneralReviewBecauseAlreadyHasReviewed(): void
	{
		$review = Review::factory()->general()->create(['customer_id' => $this->user->user->id]);

		$newReview = Review::factory()->product()->make()->toArray();

		$response = $this->withToken($this->user->token())->postJson(route('api.reviews.reviews'), $newReview);

		$response->assertStatus(403);
	}

	public function testCantCreateProductReviewBecauseAlreadyHasBeenReviewed(): void
	{
		$product = Product::factory()->create();

		Review::factory()->product()->create(['customer_id' => $this->user->user->id, 'product_id' => $product->id]);

		$newReview = Review::factory()->product()->make()->toArray();

		$response = $this->withToken($this->user->token())->postJson(route('api.products.review', ['id' => $product->id]), $newReview);

		$response->assertStatus(403);
	}

	public function testCantCreateGeneralReviewValidationError(): void
	{
		$review = Review::factory()->product()->make()->toArray();

		$response = $this->withToken($this->user->token())->postJson(route('api.reviews.reviews'), $review);

		$response->assertStatus(422);
		$response->assertInvalid([
			'description' => 'The description field is required.'
		]);
	}

	public function testCantCreateProductReviewValidationError(): void
	{
		$product = Product::factory()->create();

		$review = Review::factory()->general()->make()->toArray();

		$response = $this->withToken($this->user->token())->postJson(route('api.products.review', ['id' => $product->id]), $review);

		$response->assertStatus(422);
		$response->assertInvalid([
			'rating' => 'The rating field is required.'
		]);
	}

	/*
	 * TO DO: product review validacija da li user moze reviewati product(kad budemo napravili ordere, jer ustvari user može kreirati product review
	 * samo ako ga je kupio?
	 */
}
