<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\View\View;
use App\Http\Requests\MultiValues;
use App\Http\Requests\GlobalSearch;
use App\Http\Requests\ImportProduct;
use App\Http\Requests\UpdateProduct;
use Illuminate\Http\RedirectResponse;
use App\Services\ImportersAndHandlers\ProductImportHandler;

class ProductController extends Controller
{
	public function destroy(Product $id): RedirectResponse
	{
		$id->delete();

		return $this->redirectFromSave('products.list');
	}

	public function edit(Product $id): View
	{
		$product = $id;
		$categories = Category::with('ancestors')->available()->latest()->get();
		$variants = $id->variants()->with('translations')->get()->toArray();

		return view('products.add', compact('product', 'categories', 'variants'));
	}

	public function index(): View
	{
		$user = getUser();

		$products = Product::orderBy('name')->get();

		return view('products.list', compact('products'));
	}

	public function search(GlobalSearch $request): View
	{
		$search = $request->search;
		$products = Product::with(['category'])->search($search)->latest()->get();

		return view('products.list', compact('products', 'search'));
	}

	public function multiRemove(MultiValues $request): RedirectResponse
	{
		Product::destroy($request->values);

		return $this->redirectFromSave();
	}

	public function update(UpdateProduct $request, Product $id): RedirectResponse
	{
		$id->update([
			'description' => $request->description,
			'active' => $request->boolean('active'),
			'gratis' => $request->boolean('gratis'),
			'sort_number' => $request->sort_number,
			'piktograms' => $request->piktograms,
			'harvest' => $request->harvest,
			'category_id' => $request->category_id,
			'variant_label' => $request->variant_label,
			'unavailable' => $request->boolean('unavailable')
		]);

		$id->updateTranslations([
			'name' => [
				'en' => $request->name_en
			],
			'slug' => [
				'en' => $request->name_en ? Product::generateSlug($request->name_en) : $request->name_en
			],
			'description' => [
				'en' => $request->description_en
			],
			'variant_label' => [
				'en' => $request->variant_label_en
			]
		]);

		if($request->category_id){
			$id->saveFilterValues($request->filters);
		}




		$id->storage()->handle($request->media);

		if (($variantsCount = count($request['variants'] ?? [])) == 0) {
			$id->variants->each(function ($item) {
				$item->delete();
			});
		} else {
			$id->variants()->whereNotIn('id', $request['variant_ids'])->delete();

			for ($i = 0; $i < $variantsCount; $i++) {
				$variant = $id->variants()->updateOrCreate(['id' => $request['variant_ids'][$i]], [

					'name' => $request['variants'][$i],
					'price' => $request['variants_price'][$i],
					'measure' => $request['variants_measure'][$i],
					'weight' => $request['variants_weight'][$i],
					'product_code' => $id->code,

				]);

				$variant->updateTranslations([
					'name' => [
						'en' => $request['variants_en'][$i]
					]
				]);
			}
		}

		return $this->redirectFromSave('products.list');
	}

	public function multiActivate(MultiValues $request): RedirectResponse
	{
		Product::where('active', 0)->whereIn('id', $request->values)->update(['active' => 1]);

		return $this->redirectFromSave();
	}

	public function multiDeactivate(MultiValues $request): RedirectResponse
	{
		Product::where('active', 1)->whereIn('id', $request->values)->update(['active' => 0]);

		return $this->redirectFromSave();
	}

	public function createImport(): View
	{
		return view('products.import');
	}

	public function import(ImportProduct $request, ProductImportHandler $productImportHandler): RedirectResponse
	{
		$productImportHandler->setInitialVales($request->boolean('header_row'), 'file')->start();

		return $this->redirectFromSave('products.list');
	}
}
