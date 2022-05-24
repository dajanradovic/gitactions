<?php

use App\Models\Filter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('filters', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->string('name', 50);
			$table->boolean('active')->default(true);
			$table->boolean('searchable')->default(true);
			$table->string('display_label', 50)->nullable();
			$table->string('type', 10)->default(Filter::FILTER_TYPE_TEXT);
			$table->boolean('required')->default(false);
			$table->unsignedInteger('min')->nullable();
			$table->unsignedInteger('max')->nullable();
			$table->double('step')->nullable();
			$table->string('value', 500)->nullable();
			$table->string('message', 100)->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('filters');
	}
};
