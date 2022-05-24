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
		Schema::create('sessions', function (Blueprint $table): void {
			$table->string('id')->unique();
			$table->uuid('user_id')->nullable();
			$table->string('ip_address', 45)->nullable();
			$table->text('user_agent')->nullable();
			$table->text('payload');
			$table->unsignedInteger('last_activity');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('sessions');
	}
};
