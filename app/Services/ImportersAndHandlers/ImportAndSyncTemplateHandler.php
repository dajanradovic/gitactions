<?php

namespace App\Services\ImportersAndHandlers;

use App\Models\Category;
use Illuminate\Support\Facades\Log;
use App\Services\Support\ErpHandler;
use Illuminate\Validation\Validator;
use Illuminate\Database\Eloquent\Collection;

abstract class ImportAndSyncTemplateHandler
{
	protected int $importedItemsCount = 0;
	protected int $failedImportsCount = 0;
	protected array $failedImports = [];

	public function __construct(protected ErpHandler $erpHandler)
	{
	}

	abstract public function start(): void;

	abstract protected function createValidatorInstance(mixed ...$fieldsToValidate): Validator;

	abstract protected function updateOrCreateItem(mixed ...$productFields): void;

	protected function logStatistics(): void
	{
		Log::warning('failed sync items count', [$this->failedImportsCount]);
		Log::warning('failed imports validation errors', $this->failedImports);
		Log::warning('successful imports count', [$this->importedItemsCount]);
	}

	protected function getCategories(): Collection
	{
		return Category::get(['id', 'group_code']);
	}

	protected function getStock(): array
	{
		return $this->erpHandler->getStock()['Stock'];
	}

	protected function getProducts(): array
	{
		return $this->erpHandler->getProducts()['Products'];
	}
}
