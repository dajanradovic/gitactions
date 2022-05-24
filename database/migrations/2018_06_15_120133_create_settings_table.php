<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('settings', function (Blueprint $table): void {
			$table->uuid('id')->primary();

			$table->string('group')->index();
			$table->string('name');
			$table->boolean('locked');
			$table->json('payload');

			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('settings');
	}
};
