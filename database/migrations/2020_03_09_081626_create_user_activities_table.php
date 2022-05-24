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
		Schema::create('user_activities', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->uuid('user_id');
			$table->uuidMorphs('item');
			$table->string('ip_address', 45)->nullable();
			$table->unsignedTinyInteger('type')->default(0);
			$table->json('updated_fields')->nullable();
			$table->timestamps();

			$table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('user_activities');
	}
};
