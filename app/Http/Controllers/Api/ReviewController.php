<?php

namespace App\Http\Controllers\Api;

use App\Models\Review;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGeneralReview;
use App\Http\Resources\GeneralReviewResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewController extends Controller
{
	public function index(): JsonResource
	{
		return GeneralReviewResource::collection(Review::includes()->generalReview()->available()->paginate());
	}

	public function review(StoreGeneralReview $request): JsonResource
	{
		$customer = getUser()->user;

		$review = $customer->reviews()->create([
			'description' => $request->description
		]);

		return new GeneralReviewResource($review);
	}
}
