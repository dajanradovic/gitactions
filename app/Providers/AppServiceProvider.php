<?php

namespace App\Providers;

use App\Models\Address;
use App\Services\Config;
use Innocenzi\Vite\Vite;
use Innocenzi\Vite\Chunk;
use BigFish\PDF417\PDF417;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Services\Orders\Discounts\DiscountReturnObject;
use App\Services\Orders\Shipping\ShippingCalculationService;
use App\Services\Orders\Discounts\AboveSetPriceDiscountService;
use App\Services\Orders\Payments\PaymentSlipCreation\BarCodeGenerator;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		$this->adjustDbSettings();
		$this->setViteSettings();
		$this->bindConfigs();

		$this->app->bind(ShippingCalculationService::class, function ($app) {
			return new ShippingCalculationService(Address::$homeCountry);
		});

		$this->app->bind(BarCodeGenerator::class, function ($app) {
			return new BarCodeGenerator(new PDF417, 'HR2241240031132001585', 'HRK');
		});

		$this->app->bind(AboveSetPriceDiscountService::class, function ($app) {
			return new AboveSetPriceDiscountService(new DiscountReturnObject, setting('order_final_amount_discount_limit'));
		});
	}

	/**
	 * Register any application services.
	 */
	public function register(): void
	{
	}

	protected function adjustDbSettings(): self
	{
		Schema::defaultStringLength(191);

		return $this;
	}

	protected function setViteSettings(): self
	{
		Vite::makeScriptTagsUsing(function (string $url, ?Chunk $chunk = null): string {
			return sprintf('<script type="module" src="%s" defer></script>', $url);
		});

		Vite::makeStyleTagsUsing(function (string $url, ?Chunk $chunk = null): string {
			return sprintf('<link rel="stylesheet" type="text/css" href="%s">', $url);
		});

		return $this;
	}

	protected function bindConfigs(): self
	{
		Config::bindWithSettings();

		return $this;
	}
}
