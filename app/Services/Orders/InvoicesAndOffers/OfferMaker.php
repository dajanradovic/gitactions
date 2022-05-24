<?php

namespace App\Services\Orders\InvoicesAndOffers;

use Barryvdh\DomPDF\Facade\Pdf;

class OfferMaker extends GeneralInvoiceMaker
{
	public function __construct()
	{
		parent::__construct(new Pdf);
	}

	public function getMediaCollectionName(): string
	{
		return 'offer';
	}

	protected function getViewName(): string
	{
		return 'orders.offer';
	}
}
