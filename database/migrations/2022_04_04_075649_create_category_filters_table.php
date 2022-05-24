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
		Schema::create('category_filters', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->uuid('category_id');
			$table->uuid('filter_id');

			$table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
			$table->foreign('filter_id')->references('id')->on('filters')->onDelete('cascade');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('category_filters');
	}
};
