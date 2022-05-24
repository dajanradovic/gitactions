<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Rules\UrlExtension;
use Illuminate\Support\Facades\Validator;

class ValidationRulesTest extends TestCase
{
	public function testUrlExtensionRule(): void
	{
		$validator = Validator::make([
			'url' => 'https://example.com/image.png'
		], [
			'url' => [new UrlExtension(['jpg'])]
		]);

		$this->assertTrue($validator->fails());

		$validator = Validator::make([
			'url' => 'https://example.com/image.png'
		], [
			'url' => [new UrlExtension(['png'])]
		]);

		$this->assertFalse($validator->fails());
	}
}
