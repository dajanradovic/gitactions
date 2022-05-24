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
		Schema::dropIfExists('translations');
	}

	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('translations', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->uuidMorphs('item');
			$table->string('column', 50);
			$table->string('locale', 2);
			$table->longText('value')->nullable();
			$table->timestamps();
		});
	}
};
