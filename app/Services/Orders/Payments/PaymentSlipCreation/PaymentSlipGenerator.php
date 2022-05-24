<?php

namespace App\Services\Orders\Payments\PaymentSlipCreation;

use GdImage;
use App\Models\Setting;

class PaymentSlipGenerator
{
	private string $iban;
	private string $companyName;
	private string $companyAdditionalAddressInfo;
	private string $fontFile;
	private string $address;
	private GdImage $image;
	private string $sifraNamjene;
	private string $currency;
	private string $zipCode;
	private string $town;

	private float $fontSize = 10;
	private int $fontColor;

	public function __construct(?string $paymentSlipPath = null, ?string $fontFilePath = null, ?string $iban = null, ?string $companyName = null, ?string $address = null, ?string $town = null, ?string $zipCode = null, ?string $companyAdditionalAddressInfo = null, ?string $sifraNamjene = null, ?string $currency = null)
	{
		$this->image = imagecreatefromjpeg($paymentSlipPath ?? env('PAYMENT_SLIP_PATH'));
		$this->fontFile = $fontFilePath ?? env('PAYMENT_SLIP_FONT_PATH');
		$this->fontColor = imagecolorallocate($this->image, 0, 0, 0);
		$this->iban = $iban ?? setting('iban');
		$this->companyName = $companyName ?? setting('company_name');
		$this->address = $address ?? setting('company_address');
		$this->town = $town ?? setting('company_town');
		$this->zipCode = $zipCode ?? setting('company_zip_code');
		$this->companyAdditionalAddressInfo = $companyAdditionalAddressInfo ?? setting('company_additional_address_info');
		$this->sifraNamjene = $sifraNamjene ?? setting('sifra_namjene');
		$this->currency = $currency ?? Setting::getMainCurrency();
	}

	public function generate(string $amount, string $customerName, string $orderNumber): self
	{
		$this->write(40, 195, $this->companyName)
			->write(40, 215, $this->createCompanyAddress())
			->write(40, 235, $this->companyAdditionalAddressInfo)
			->write(480, 143, $this->iban, 12)
			->write(255, 180, $this->sifraNamjene, 12)
			->write(400, 205, 'Broj narudžbe - ' . $orderNumber)
			->write(40, 60, $customerName)
			->write(370, 47, $this->currency, 12)
			->write(830, 45, $this->currency . ' ' . $amount)
			->write(640, 45, $amount, 12)
			->write(740, 145, $this->iban)
			->write(780, 80, $customerName)
			->write(740, 230, 'Broj narudžbe - ' . $orderNumber);

		return $this;
	}

	public function applyBarCode(string $orderNumber, string $barCodePath): void
	{
		$quality = 100; // 0 to 100
		imagejpeg($this->image, storage_path('app') . "/uplatnica-bez-barcode-$orderNumber.jpg", $quality); // pretvara defaultnu uplatnicu  potreban format
		$newImage = imagecreatefrompng($barCodePath);
		imagecopy($this->image, $newImage, 33, 270, 0, 0, 156, 80);

		imagejpeg($this->image, storage_path('app') . "/uplatnica-sa-barcode-$orderNumber.jpg", $quality);
	}

	public function createCompanyAddress(): string
	{
		return $this->address . ', ' . $this->zipCode . ' ' . $this->town;
	}

	private function write(float|int|string $posX, float|int|string $posY, string $txt, ?float $fontSize = null): self
	{
		imagettftext($this->image, $fontSize ?? $this->fontSize, $angle = 0, $posX, $posY, $this->fontColor, $this->fontFile, $txt);

		return $this;
	}
}
