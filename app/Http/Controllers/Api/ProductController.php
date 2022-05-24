<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Mail\ProductInquiryMail;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LangRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\FilterProduct;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ProductFilters;
use App\Http\Requests\ProductInquiry;
use App\Http\Requests\StoreProductReview;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProductFilterResource;
use App\Http\Resources\ProductReviewResource;
use App\Http\Resources\ProductSingleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductController extends Controller
{
	public function index(FilterProduct $request): JsonResource
	{
		return ProductListResource::collection(Product::includes($request->lang)
		//	->available()
		//	->search($request->search, $request->lang)
		//	->adultOnly($request->adult_only)
			/*->filterPriceRange($request->price_min, $request->price_max)
			->filterVolume($request->volume)
			->filterCategory($request->category)
			->filterSortBy($request->sort_by, $request->sort_by_field)*/
			->dynamicFilters($request->filters)
			->orderBy('sort_number', 'desc')
			->latest()
			->paginate($request->per_page)
			->appends($request->query()));
	}

	public function single(string $product, LangRequest $request): JsonResource
	{
		$product = Product::includes($request->lang)->available()->where(function ($query) use ($product) {
			$query->where('id', $product)
				->orWhere('slug', $product)
				->orWhereHas('translations', function ($query) use ($product) {
					$query->where([
						['column', 'slug'],
						['value', $product],
					]);
				});
		})->firstOrFail();

		return new ProductSingleResource($product);
	}

	public function filters(ProductFilters $request): JsonResource
	{
		return ProductFilterResource::collection(Product::findOrFail($request->product_id)->productFilters()->available()->latest()->get());
	}

	public function inquiry(Product $id, ProductInquiry $request): JsonResponse
	{
		try {
			Mail::send(new ProductInquiryMail($request->email, $request->message, $request->type, $id->name, $id->code));
		} catch (Exception) {
			return response()->json(['data' => [
				'success' => false
			]], 500);
		}

		return response()->json(['data' => [
			'success' => true
		]], 200);
	}

	public function upsell(Product $id, Request $request): JsonResource
	{
		return ProductListResource::collection(Product::includes($request->lang)
			->available()
			->upsell($id)
			->take(10)
			->get());
	}

	public function review(StoreProductReview $request, Product $id): JsonResource
	{
		$review = getUser()->user->reviews()->create([
			'product_id' => $id->id,
			'rating' => $request->rating
		]);

		return new ProductReviewResource($review);
	}

	public function getReviews(Product $id): JsonResource
	{
		return ProductReviewResource::collection($id->reviews()->productReview()->available()->paginate());
	}

	public function like(Product $id): JsonResource
	{
		$user = auth()->user()->user;

		$like_exists = $id->likes()->where('customer_id', $user->id)->exists();

		if ($like_exists) {
			$id->likes()->where('customer_id', $user->id)->delete();
		} else {
			$id->likes()->create([
				'customer_id' => $user->id
			]);
		}

		return new ProductSingleResource($id);
	}
}
