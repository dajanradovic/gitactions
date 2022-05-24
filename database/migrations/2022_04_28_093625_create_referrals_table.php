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
		Schema::create('referrals', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->string('email')->unique();
			$table->uuid('referrer_id');
			$table->timestamp('resolved_at')->nullable();
			$table->timestamps();

			$table->foreign('referrer_id')->references('id')->on('customers')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('referrals');
	}
};
