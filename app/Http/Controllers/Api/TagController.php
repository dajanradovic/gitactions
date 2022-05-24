<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use App\Http\Requests\SearchTerm;
use App\Http\Resources\TagResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class TagController extends Controller
{
	public function index(SearchTerm $request): JsonResource
	{
		return TagResource::collection(Tag::available()
			->search($request->search)
			->orderBy('name')
			->simplePaginate()
			->withQueryString());
	}
}
