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
		Schema::create('customers', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->string('surname', 80);
			$table->string('oib', 30)->nullable();
			$table->date('date_of_birth')->nullable();
			$table->string('company_name', 100)->nullable();
			$table->boolean('newsletter')->default(false);
			$table->boolean('club_card')->default(false);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('customers');
	}
};
