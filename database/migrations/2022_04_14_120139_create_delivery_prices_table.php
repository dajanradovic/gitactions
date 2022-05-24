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
		Schema::create('delivery_prices', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->string('country_code', 10);
			$table->string('delivery_service', 20);
			$table->unsignedSmallInteger('up_to_2_kg')->default(0);
			$table->unsignedSmallInteger('up_to_5_kg')->default(0);
			$table->unsignedSmallInteger('up_to_10_kg')->default(0);
			$table->unsignedSmallInteger('up_to_15_kg')->default(0);
			$table->unsignedSmallInteger('up_to_20_kg')->default(0);
			$table->unsignedSmallInteger('up_to_25_kg')->default(0);
			$table->unsignedSmallInteger('up_to_32_kg')->default(0);
			$table->unsignedSmallInteger('islands_extra')->default(0);
			$table->unsignedSmallInteger('additional_costs')->default(0);

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('delivery_prices');
	}
};
