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
		Schema::dropIfExists('notification_targets');
	}

	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('notification_targets', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->uuid('notification_id');
			$table->uuidMorphs('user');
			$table->dateTime('seen_at')->nullable();
			$table->timestamps();

			$table->foreign('notification_id')->references('id')->on('notifications')->cascadeOnDelete();
		});
	}
};
