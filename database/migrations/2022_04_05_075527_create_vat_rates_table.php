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
		Schema::create('vat_rates', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->string('country_code', 10);
			$table->uuid('category_id');
			$table->float('amount')->default(0);

			$table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('vat_rates');
	}
};
