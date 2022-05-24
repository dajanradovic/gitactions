<?php

namespace App\Http\Controllers\Api;

use App\Models\Banner;
use App\Http\Requests\GetBanner;
use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerController extends Controller
{
	public function index(GetBanner $request): JsonResource
	{
		return BannerResource::collection(Banner::includes()->available()->bannerType($request->banner_type)->orderBy('order_column')->get());
	}
}
