<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('password_resets');
	}

	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('password_resets', function (Blueprint $table): void {
			$table->string('email', 50)->index();
			$table->string('token')->unique();
			$table->timestamp('created_at')->nullable();
		});
	}
};
