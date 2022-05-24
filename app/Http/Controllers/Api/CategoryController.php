<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\LangRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchCategory;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategorySingleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryController extends Controller
{
	public function index(SearchCategory $request): JsonResource
	{
		return CategoryResource::collection(Category::includes($request->lang)
			->available()
			->parent($request->has('parent'), $request->parent)
			->search($request->search, $request->lang)
			->orderBy('name')
			->paginate()
			->appends($request->query()));
	}

	public function single(string $category, LangRequest $request): JsonResource
	{
		$category = Category::includes($request->lang)->available()->where(function ($query) use ($category) {
			$query->where('id', $category)
				->orWhere('slug', $category)
				->orWhereHas('translations', function ($query) use ($category) {
					$query->where([
						['column', 'slug'],
						['value', $category],
					]);
				});
		})->firstOrFail();

		return new CategorySingleResource($category);
	}

	public function filters(Request $request): JsonResource
	{
		return new CategorySingleResource(Category::find($request->category_id));
	}
}
