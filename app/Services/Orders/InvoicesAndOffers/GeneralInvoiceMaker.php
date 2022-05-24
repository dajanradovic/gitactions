<?php

namespace App\Services\Orders\InvoicesAndOffers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

abstract class GeneralInvoiceMaker
{
	private ?string $company_name = null;
	private ?string $company_address = null;
	private ?string $company_town = null;
	private ?string $company_zip_code = null;

	public function __construct(private Pdf $pdf)
	{
		$this->company_name = setting('company_name') ?? null;
		$this->company_town = setting('company_town') ?? null;
		$this->company_zip_code = setting('company_zip_code') ?? null;
		$this->company_address = setting('company_address') ?? null;
	}

	public function loadView(Order $order): \Barryvdh\DomPDF\PDF
	{
		return $this->pdf::loadView($this->getViewName(), ['order' => $order,
			'company_name' => $this->company_name,
			'company_town' => $this->company_town,
			'company_zip_code' => $this->company_zip_code,
			'company_address' => $this->company_address
		]);
	}

	abstract public function getMediaCollectionName(): string;

	abstract protected function getViewName(): string;
}
