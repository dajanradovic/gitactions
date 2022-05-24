<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Product
 *
 * @property int|null $reviews_avg_rating
 * */
class ProductListResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 */
	public function toArray($request): array
	{
		$lang = $request->lang;
		$discount = $this->getCurrentlyApplicableDiscount();
		$discountPrice = $this->applyDiscount($discount);

		return [
			'id' => $this->id,
			'type' => $this->type,
			'currency' => Setting::getMainCurrency(),
			'name' => $this->determineTranslation($lang, 'name'),
			'slug' => $this->determineTranslation($lang, 'slug'),
			'description' => $this->determineTranslation($lang, 'description'),
			'price' => $this->price,
			'price_with_discount' => $discountPrice,
			'availability' => $this->availabilityStatus(),
			'variant_label' => $this->determineTranslation($lang, 'variant_label'),
			'second_price' => Product::getSecondPrice($this->price),
			'second_price_with_discount' => Product::getSecondPrice($discountPrice),
			'vat_rate' => $this->getVatRate(),
			'code' => $this->code,
			'harvest' => $this->harvest,
			'applied_discount' => new DiscountResource($discount),
			'category' => new CategorySingleResource($this->category),
			'piktograms' => $this->fetchPiktograms($discount),
			'variants' => ProductVariantResource::collection($this->variants),
			'media' => $this->storage()->getFirstThumb('image'),
			'rating' => $this->reviews_avg_rating ? round($this->reviews_avg_rating, 2) : null,
			'user_liked' => $this->hasUserLiked(),
			'created_at' => formatTimestamp($this->created_at),
			'updated_at' => formatTimestamp($this->updated_at),
		];
	}
}
