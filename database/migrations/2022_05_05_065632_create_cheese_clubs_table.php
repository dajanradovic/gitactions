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
		Schema::create('cheese_clubs', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->string('name', 50)->nullable();
			$table->string('surname', 80)->nullable();
			$table->string('email', 50)->unique();
			$table->tinyInteger('club_type')->nullable();
			$table->date('date_of_birth')->nullable();
			$table->unsignedInteger('points')->default(0);
			$table->string('card_number')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('cheese_clubs');
	}
};
