<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use App\Jobs\Middleware\BindConfigs;
use App\Services\Support\ErpHandler;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\ImportersAndHandlers\ProductSyncHandler;

class SyncProducts implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public bool $deleteWhenMissingModels = true;

	/**
	 * Create a new job instance.
	 */
	public function __construct()
	{
	}

	public function middleware(): array
	{
		return [new BindConfigs];
	}

	/**
	 * Execute the job.
	 */
	public function handle(): void
	{
		$syncProducts = new ProductSyncHandler;
		$syncProducts->start();

		/*$erpHandler = new ErpHandler;

		$productsCollection = $erpHandler->getProducts()['Products'];

		$stock = collect($erpHandler->getStock()['Stock']);

		$categories = Category::get(['id', 'group_code']);

		$importedItemsCount = 0;
		$failedImportsCount = 0;
		$failedImports = [];

		foreach ($productsCollection as $item) {
			$productInStock = $stock->firstWhere('ProductCode', $item['ProductCode']);

			$name = $item['ProductName'];
			$slug = Product::generateSlug($item['ProductName']);
			$price = $productInStock['Price'] ?? 0;
			$quantity = $productInStock['Quantity'] ?? 0;
			$category_id = $categories->firstWhere('group_code', $item['ProductGroupCode'])['id'] ?? null;
			$weight = $productInStock['Weight'] ?? 0;
			$type = Product::REGULAR_PRODUCT;
			$unit_of_measure = array_search(strtoupper($item['UnitOfMeasure']), Product::getMeasureUnites());
			$code = $item['ProductCode'];

			$validator = Validator::make([
				'name' => $name,
				'slug' => $slug,
				'price' => $price,
				'quantity' => $quantity,
				'category_id' => $category_id,
				'weight' => $weight,
				'type' => $type,
				'unit_of_measure' => $unit_of_measure,
				'code' => $code,
			], [
				'name' => ['required', 'string', 'max:100'],
				'slug' => ['required', 'string', 'max:100'],
				'price' => ['required', 'numeric'],
				'quantity' => ['required', 'numeric'],
				'category_id' => ['required', 'uuid'],
				'weight' => ['nullable', 'numeric', 'min:0'],
				'type' => ['required', 'integer'],
				'unit_of_measure' => ['required', 'numeric', 'min:0'],
				'code' => ['required', 'string']
			]);

			if ($validator->fails()) {
				$failedImportsCount++;
				$failedImports['items'][$item['ProductGroupCode']] = $validator->errors();

				continue;
			}

			Product::updateOrCreate(['code' => $item['ProductCode']], [
				'name' => $name,
				'slug' => $slug,
				'price' => $price,
				'quantity' => $quantity,
				'category_id' => $category_id,
				'weight' => $weight,
				'type' => $type,
				'unit_of_measure' => $unit_of_measure,
				'code' => $code

			]);

			$importedItemsCount++;
		}

		Log::warning('failed sync items count', [$failedImportsCount]);
		Log::warning('failed imports validation errors', $failedImports);
		Log::warning('successful imports count', [$importedItemsCount]);*/
	}
}
