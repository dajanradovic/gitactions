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
		Schema::create('likes', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->uuidMorphs('item');
			$table->uuid('customer_id');
			$table->timestamps();

			$table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('likes');
	}
};
