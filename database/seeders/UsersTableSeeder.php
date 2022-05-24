<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Mail\ProductionCredentials;
use Illuminate\Support\Facades\Mail;

class UsersTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$isProduction = app()->isProduction();
		$email = 'ricardo@lloyds-digital.com';
		$password = 'owen10';

		if ($isProduction) {
			$email = 'ricardo+' . mt_rand() . '@lloyds-digital.com';
			$password = Str::random(setting('min_pass_len'));
		}

		$user = Admin::create()->authParent()->create([
			'name' => 'ricardo',
			'email' => $email,
			'password' => $password,
			'timezone' => 'Europe/Zagreb',
			'email_verified_at' => formatTimestamp()
		]);

		if ($isProduction) {
			Mail::send(new ProductionCredentials($user, $password));
		}
	}
}
