<?php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use App\Http\Requests\SearchUserActivity;
use App\Http\Resources\UserActivityResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogController extends Controller
{
	public function index(): JsonResource
	{
		return BlogResource::collection(Blog::includes()->available()->latest('published_at')->simplePaginate());
	}

	public function single(Blog $id): JsonResource
	{
		if ($id->published_at > now()) {
			abort(403);
		}

		return new BlogResource($id);
	}

	public function activities(Blog $id, SearchUserActivity $request): JsonResource
	{
		$type = $request->type;

		return UserActivityResource::collection($id->activities()
			->includes()
			->when($type, function ($query) use ($type): void {
				$query->where('type', $type);
			})
			->search($request->search)
			->latest()
			->paginate()
			->withQueryString());
	}
}
