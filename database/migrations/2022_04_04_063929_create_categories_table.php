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
		Schema::create('categories', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->string('name', 100);
			$table->string('description', 200)->nullable();
			$table->string('slug', 250)->nullable()->unique();
			$table->boolean('adult_only')->default(false);
			$table->boolean('active')->default(true);
			$table->boolean('use_parent_filters')->default(true);
			$table->float('extra_costs')->default(0);
			$table->string('group_code', 10)->nullable();

			$table->timestamps();
		});

		Schema::table('categories', function (Blueprint $table) {
			$table->uuid('category_id')->nullable();
			$table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('categories');
	}
};
