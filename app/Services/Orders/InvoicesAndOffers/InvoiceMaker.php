<?php

namespace App\Services\Orders\InvoicesAndOffers;

use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceMaker extends GeneralInvoiceMaker
{
	public function __construct()
	{
		parent::__construct(new Pdf);
	}

	public function getMediaCollectionName(): string
	{
		return 'invoice';
	}

	protected function getViewName(): string
	{
		return 'orders.invoice';
	}
}
