<?php

namespace App\Services\ImportersAndHandlers;

use App\Models\Product;
use Illuminate\Support\Facades\App;
use App\Services\Support\ErpHandler;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as ValidationValidator;

class ProductSyncHandler extends ImportAndSyncTemplateHandler
{
	public function __construct()
	{
		parent::__construct(App::make(ErpHandler::class));
	}

	public function start(): void
	{
		$productsCollection = $this->getProducts();
		$stock = collect($this->getStock());

		foreach ($productsCollection as $item) {
			$productInStock = $stock->firstWhere('ProductCode', $item['ProductCode']);

			$quantity = $productInStock['Quantity'] ?? 0;
			$weight = $productInStock['Weight'] ?? 0;
			$code = $item['ProductCode'];

			$validator = $this->createValidatorInstance($quantity, $weight, $code);

			if ($validator->fails()) {
				$this->failedImportsCount++;
				$this->failedImports['items'][$item['ProductGroupCode']] = $validator->errors();

				continue;
			}

			$this->updateOrCreateItem($quantity, $weight, $code);

			$this->importedItemsCount++;
		}

		$this->logStatistics();
	}

	protected function createValidatorInstance(mixed ...$arguments): ValidationValidator
	{
		[$quantity, $weight, $code] = $arguments;

		return Validator::make([
			'quantity' => $quantity,
			'weight' => $weight,
			'code' => $code,
		], [
			'quantity' => ['required', 'numeric'],
			'weight' => ['nullable', 'numeric', 'min:0'],
			'code' => ['required', 'string']

		]);
	}

	protected function updateOrCreateItem(mixed ...$arguments): void
	{
		[$quantity, $weight, $code] = $arguments;

		$product = Product::where('code', $code)->first();

		if ($product) {
			$product->update([
				'quantity' => $quantity,
				'weight' => $weight
			]);
		}
	}
}
