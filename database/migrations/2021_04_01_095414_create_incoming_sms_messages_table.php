<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('incoming_sms_messages', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->unsignedTinyInteger('provider');
			$table->string('external_id', 50);
			$table->string('from', 20);
			$table->string('to', 20);
			$table->string('body', 1600);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('incoming_sms_messages');
	}
};
