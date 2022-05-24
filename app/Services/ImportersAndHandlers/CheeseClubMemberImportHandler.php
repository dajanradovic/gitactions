<?php

namespace App\Services\ImportersAndHandlers;

use App\Models\CheeseClub;
use Illuminate\Support\Facades\App;
use App\Services\Support\ErpHandler;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Validation\Validator as ValidationValidator;

class CheeseClubMemberImportHandler extends ImportAndSyncTemplateHandler
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

		while ($data = fgetcsv($csvFile)) {
			if ($this->skipHeaderRow) {
				$this->skipHeaderRow = false;

				continue;
			}

			$email = $data[0];
			$name = $data[1];
			$surname = $data[1];
			$card_number = $data[17];

			$validator = $this->createValidatorInstance($email, $name, $surname, $card_number);

			if ($validator->fails()) {
				$this->failedImportsCount++;
				$this->failedImports['items'][$data[0]] = $validator->errors();

				continue;
			}

			$this->updateOrCreateItem($email, $name, $surname, $card_number);

			$this->importedItemsCount++;
		}

		fclose($csvFile);

		$this->logStatistics();
	}

	protected function createValidatorInstance(mixed ...$arguments): ValidationValidator
	{
		[$email, $name, $surname, $card_number] = $arguments;

		return Validator::make([
			'email' => $email,
			'name' => $name,
			'surname' => $surname,
			'card_number' => $card_number,
		], [
			'email' => ['required', 'string', 'max:50'],
			'name' => ['nullable', 'string', 'max:50'],
			'surname' => ['nullable', 'string', 'max:80'],
			'card_number' => ['nullable', 'string'],
		]);
	}

	protected function updateOrCreateItem(mixed ...$arguments): void
	{
		[$email, $name, $surname, $card_number] = $arguments;

		CheeseClub::updateOrCreate(['email' => $email], [
			'name' => $name,
			'surname' => $surname,
			'card_number' => $card_number,
		]);
	}
}
