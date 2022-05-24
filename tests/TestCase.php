<?php

namespace Tests;

use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
	use CreatesApplication, DatabaseTransactions, WithFaker, TestHelpers;

	public function __construct(?string $name = null, array $data = [], int|string $dataName = '')
	{
		parent::__construct($name, $data, $dataName);

		Mail::fake();
	}
}
