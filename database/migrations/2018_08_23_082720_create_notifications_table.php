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
		Schema::dropIfExists('notifications');
	}

	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('notifications', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->nullableUuidMorphs('parent');
			$table->string('title', 50);
			$table->string('body', 2000);
			$table->string('url', 1000)->nullable();
			$table->json('countries')->nullable();
			$table->unsignedInteger('radius')->nullable();
			$table->double('location_lat')->nullable();
			$table->double('location_lng')->nullable();
			$table->dateTime('scheduled_at')->useCurrent();
			$table->string('collapse_id', 64)->nullable();
			$table->string('external_id', 128)->nullable();
			$table->unsignedSmallInteger('remaining')->default(0);
			$table->unsignedSmallInteger('successful')->default(0);
			$table->unsignedSmallInteger('failed')->default(0);
			$table->unsignedSmallInteger('errored')->default(0);
			$table->unsignedSmallInteger('converted')->default(0);
			$table->boolean('canceled')->default(false);
			$table->boolean('active')->default(true);
			$table->dateTime('completed_at')->nullable();
			$table->timestamps();
		});
	}
};
