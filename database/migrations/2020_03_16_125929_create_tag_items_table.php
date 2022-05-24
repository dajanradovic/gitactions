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
		Schema::create('tag_items', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->uuid('tag_id');
			$table->uuidMorphs('item');
			$table->timestamps();

			$table->foreign('tag_id')->references('id')->on('tags')->cascadeOnDelete();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('tag_items');
	}
};
