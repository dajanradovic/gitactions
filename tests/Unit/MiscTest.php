<?php

namespace Tests\Unit;

use Tests\TestCase;

class MiscTest extends TestCase
{
	public function testStringifyAttributes(): void
	{
		$data = stringifyAttr([
			'name' => 'Test Name',
			'accepted' => true,
			'email' => 'test email',
			'multiple' => false,
		]);

		$this->assertEquals($data, 'name="Test Name" accepted email="test email"');
	}
}
