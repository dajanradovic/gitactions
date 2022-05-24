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
		Schema::create('discounts', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->string('title', 100);
			$table->unsignedTinyInteger('type');
			$table->unsignedSmallInteger('max_use')->default(0);
			$table->dateTime('period_from')->nullable();
			$table->dateTime('period_to')->nullable();
			$table->string('code', 10)->nullable()->unique();
			$table->float('amount')->unsigned();
			$table->boolean('is_percentage')->default(true);
			$table->boolean('active')->default(true);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('discounts');
	}
};
