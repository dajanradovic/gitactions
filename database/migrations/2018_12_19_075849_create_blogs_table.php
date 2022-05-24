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
		Schema::dropIfExists('blogs');
	}

	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('blogs', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->string('title', 50);
			$table->string('slug', 50)->unique();
			$table->string('body', 5000);
			$table->dateTime('published_at')->useCurrent();
			$table->timestamps();
		});
	}
};
