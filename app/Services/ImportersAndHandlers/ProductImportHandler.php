<?php

namespace App\Services\ImportersAndHandlers;

use App\Models\Product;
use Illuminate\Support\Facades\App;
use App\Services\Support\ErpHandler;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Validation\Validator as ValidationValidator;

class ProductImportHandler extends ImportAndSyncTemplateHandler
{
	private bool $skipHeaderRow = true;
	private ?string $fileForm = null;

	public function __construct()
	{
		parent::__construct(App::make(ErpHandler::class));
	}

	public function setInitialVales(bool $headerRow, string $fileForm): self
	{
		$this->skipHeaderRow = $headerRow;
		$this->fileForm = $fileForm;

		return $this;
	}

	public function start(): void
	{
		$request = request();

		$csvFile = fopen($request->file($this->fileForm)->getPathname(), 'r');

		if (!$csvFile) {
			throw new FileNotFoundException;
		}

		$productsCollection = collect($this->getProducts());
		$stock = collect($this->getStock());

		$categories = $this->getCategories();

		while ($data = fgetcsv($csvFile)) {
			if ($this->skipHeaderRow) {
				$this->skipHeaderRow = false;

				continue;
			}

			$productCode = $data[0];

			$productInStock = $stock->firstWhere('ProductCode', $productCode);

			$name = $data[1];
			$slug = Product::generateSlug($data[1]);
			$price = $data[5] ?? 0;
			$quantity = $productInStock['Quantity'] ?? 0;
			$category_id = $categories->firstWhere('group_code', $data[2])['id'] ?? null;
			$weight = $productInStock['Weight'] ?? 0;
			$type = Product::REGULAR_PRODUCT;
			$unit_of_measure = array_search($productsCollection->firstWhere('ProductCode', $productCode)['UnitOfMeasure'], Product::getMeasureUnites());

			$validator = $this->createValidatorInstance($name, $slug, $price, $quantity, $category_id, $weight, $type, $unit_of_measure, $productCode);

			if ($validator->fails()) {
				$this->failedImportsCount++;
				$this->failedImports['items'][$data[0]] = $validator->errors();

				continue;
			}

			$this->updateOrCreateItem($name, $slug, $price, $quantity, $category_id, $weight, $type, $unit_of_measure, $productCode);

			$this->importedItemsCount++;
		}

		fclose($csvFile);

		$this->logStatistics();
	}

	protected function createValidatorInstance(mixed ...$arguments): ValidationValidator
	{
		[$name, $slug, $price, $quantity, $category_id, $weight, $type, $unit_of_measure, $code] = $arguments;

		return Validator::make([
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
	}

	protected function updateOrCreateItem(mixed ...$arguments): void
	{
		[$name, $slug, $price, $quantity, $category_id, $weight, $type, $unit_of_measure, $code] = $arguments;

		Product::updateOrCreate(['code' => $code], [
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
	}
}
