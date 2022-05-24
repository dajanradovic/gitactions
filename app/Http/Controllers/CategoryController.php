<?php

namespace App\Http\Controllers;

use App\Models\Filter;
use App\Models\Category;
use Illuminate\View\View;
use App\Jobs\SyncProducts;
use App\Models\Translation;
use Illuminate\Validation\Rule;
use App\Http\Requests\MultiValues;
use App\Http\Requests\StoreCategory;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
	public function create(): View
	{
		$categories = Category::with('ancestors')->latest()->get();
		$filters = Filter::latest()->get();

		$category = new Category;

		return view('categories.add', compact('categories', 'filters', 'category'));
	}

	public function destroy(Category $id): RedirectResponse
	{
		$id->delete();

		return $this->redirectFromSave('categories.list');
	}

	public function edit(Category $id): View
	{
		$category = $id;
		$categories = Category::with('ancestors')->where('id', '<>', $id->id)->latest()->get();
		$filters = Filter::latest()->get();

		$vatRates = $category->vatRates()->get(['country_code', 'amount']);

		return view('categories.add', compact('category', 'categories', 'filters', 'vatRates'));
	}

	public function index(): View
	{
		// SyncProducts::dispatchSync();

		$categories = Category::with(['parent', 'rootAncestor'])->latest()->get();

		return view('categories.list', compact('categories'));
	}

	public function multiRemove(MultiValues $request): RedirectResponse
	{
		Category::destroy($request->values);

		return $this->redirectFromSave();
	}

	public function store(StoreCategory $request): RedirectResponse
	{
		$request->validate([
			'slug' => ['required', 'string', 'max:50', 'unique:' . Category::class],
			'slug_en' => [
				'nullable', 'string', 'max:50', Rule::unique(Translation::class, 'value')
					->where(function ($query) {
						return $query->where('item_type', Category::class);
					})
			],
		]);

		$category = Category::create([
			'category_id' => $request->category_id,
			'name' => $request->name,
			'slug' => $request->slug,
			'active' => $request->boolean('active'),
			'use_parent_filters' => $request->boolean('use_parent_filters'),
			'description' => $request->description,
			'adult_only' => $request->boolean('adult_only'),
			'extra_costs' => $request->extra_costs ?? 0,
			'group_code' => $request->group_code
		]);

		$category->updateTranslations([
			'name' => [
				'en' => $request->name_en
			],
			'slug' => [
				'en' => $request->slug_en
			],
			'description' => [
				'en' => $request->description_en
			]
		]);

		$category->storage()->handle();
		$filters = $request->selected_filters ?? [];

		foreach ($filters as $filter_id) {
			$category->categoryFilters()->create([
				'filter_id' => $filter_id
			]);
		}

		foreach ($request->countries as $index => $value) {
			$category->vatRates()->create([
				'country_code' => $index,
				'amount' => $value ?: 0
			]);
		}

		return $this->redirectFromSave('categories.list');
	}

	public function update(StoreCategory $request, Category $id): RedirectResponse
	{
		$request->validate([
			'slug' => ['required', 'string', 'max:50', 'unique:' . get_class($id) . ',slug,' . $id->id],
			'slug_en' => [
				'required', 'string', 'max:50',
				Rule::unique(Translation::class, 'value')
					->ignore($id->id, 'item_id')
					->where(function ($query) use ($id) {
						return $query->where('item_type', get_class($id));
					})
			],
		]);

		$id->update([
			'category_id' => $request->category_id,
			'name' => $request->name,
			'slug' => $request->slug,
			'active' => $request->boolean('active'),
			'use_parent_filters' => $request->boolean('use_parent_filters'),
			'description' => $request->description,
			'adult_only' => $request->boolean('adult_only'),
			'extra_costs' => $request->extra_costs ?? 0,
			'group_code' => $request->group_code
		]);

		$id->updateTranslations([
			'name' => [
				'en' => $request->name_en
			],
			'slug' => [
				'en' => $request->slug_en
			],
			'description' => [
				'en' => $request->description_en
			]
		]);

		$id->storeFilters($request->selected_filters);
		$id->storage()->handle($request->media);

		foreach ($request->countries as $index => $value) {
			$id->vatRates()->updateOrCreate(['country_code' => $index], ['amount' => $value ?: 0]);
		}

		return $this->redirectFromSave('categories.list');
	}

	public function multiActivate(MultiValues $request): RedirectResponse
	{
		Category::where('active', 0)->whereIn('id', $request->values)->update(['active' => 1]);

		return $this->redirectFromSave();
	}

	public function multiDeactivate(MultiValues $request): RedirectResponse
	{
		Category::where('active', 1)->whereIn('id', $request->values)->update(['active' => 0]);

		return $this->redirectFromSave();
	}
}
