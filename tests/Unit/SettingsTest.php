<?php

namespace Tests\Unit;

use Tests\TestCase;

class SettingsTest extends TestCase
{
	public function testSettingGetters(): void
	{
		$this->assertEquals(setting('app_name'), setting()->app_name);
	}

	public function testInvalidKeyWithDefault(): void
	{
		$this->assertEquals(setting('x', 'y'), 'y');
	}
}
