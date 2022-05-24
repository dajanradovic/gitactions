<?php

namespace Tests\Unit;

use DateTime;
use Tests\TestCase;

class DateTimeTest extends TestCase
{
	public function testFormatTimestampHelper(): void
	{
		$carbonNow = now();
		$carbonNowString = $carbonNow->format('Y-m-d H:i:s');
		$dateTimeNow = new DateTime;
		$dateTimeNowString = $dateTimeNow->format('Y-m-d H:i:s');

		$nowString = formatTimestamp();

		$this->assertEquals(formatTimestamp(time()), $nowString);

		$this->assertEquals($nowString, $carbonNowString);

		$this->assertEquals(formatTimestamp($carbonNow), $carbonNowString);

		$this->assertEquals(formatTimestamp($carbonNowString), $carbonNowString);

		$this->assertEquals($nowString, $dateTimeNowString);

		$this->assertEquals(formatTimestamp($dateTimeNow), $dateTimeNowString);

		$this->assertEquals(formatTimestamp($dateTimeNowString), $dateTimeNowString);
	}
}
