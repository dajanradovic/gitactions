<?php

namespace App\Services\Orders\Payments\PaymentSlipCreation;

use BigFish\PDF417\PDF417;

use BigFish\PDF417\BarcodeData;
use BigFish\PDF417\Renderers\ImageRenderer;

class BarCodeGenerator
{
	public function __construct(private PDF417 $barCodeGenerator, public string $iban, public string $currency)
	{
	}

	public function generate(string $amount, string $referenceNumber, string $customerName, string $path): void
	{
		$template = $this->fillInTemplate($amount, $referenceNumber, $customerName);

		$this->convertBarCodeToImage($this->encodeBarCode($template), $referenceNumber, $path);
	}

	public function encodeBarCode(string $data): BarcodeData
	{
		$this->barCodeGenerator->setColumns(5);
		$this->barCodeGenerator->setSecurityLevel(4);

		return $this->barCodeGenerator->encode($data);
	}

	public function convertBarCodeToImage(object $data, string $referenceNumber, string $path): void
	{
		// Create a PNG image
		$renderer = new ImageRenderer([
			'format' => 'png',
			'padding' => 0,
			'scale' => 1,
			'ratio' => 3,
		]);

		$image = $renderer->render($data);
		$image->save($path . "/barkod-$referenceNumber.png");
	}

	private function fillInTemplate(string $amount, string $referenceNumber, string $customerName): string
	{
		$price = $this->generateTemplatePrice($amount);

		// !!! RAZMACI MORAJU BITI !!!
		return "HRVHUB30
			$this->currency
			$price
			$customerName





			$this->iban
			HR01
			$referenceNumber
			COST
			Plaćanje narudžbe broj $referenceNumber";

		// $template = "HRVHUB30
		// HRK
		// 000000000012355
		// ŽELJKO SENEKOVIĆ
		// IVANEČKA ULICA 125
		// 42000 VARAŽDIN
		// 2DBK d.d.
		//     ALKARSKI PROLAZ 13B
		// 21230 SINJ
		// HR1210010051863000160
		// HR01
		// 7269-68949637676-00019
		// COST
		// Troškovi za 1. mjesec";
	}

	private function generateTemplatePrice(string $amount): string
	{
		$explodedString = explode('.', $amount);

		$finalString = '';

		$i = 15 - strlen($explodedString[0]) - 2;
		$j = 1;

		while ($j <= $i) {
			$finalString .= '0';

			$j++;
		}

		return $finalString .= isset($explodedString[1]) ? $explodedString[0] . (strlen($explodedString[1]) == 1 ? $explodedString[1] . '0' : $explodedString[1]) : $explodedString[0] . '00';
	}
}
