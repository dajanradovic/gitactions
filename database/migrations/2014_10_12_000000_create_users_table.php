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
		Schema::dropIfExists('users');
	}

	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('users', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->uuid('role_id')->nullable();
			$table->uuidMorphs('user');
			$table->string('name', 50);
			$table->string('email', 50)->unique();
			$table->string('password', 256)->nullable();
			$table->boolean('allow_push_notifications')->default(true);
			$table->boolean('active')->default(true);
			$table->string('timezone', 50)->default('UTC');
			$table->string('locale', 2)->default(config('app.locale'));
			$table->string('avatar', 1000)->nullable();
			$table->string('bitbucket', 128)->nullable();
			$table->string('github', 128)->nullable();
			$table->string('gitlab', 128)->nullable();
			$table->string('facebook', 128)->nullable();
			$table->string('twitter', 128)->nullable();
			$table->string('google', 128)->nullable();
			$table->string('linkedin', 128)->nullable();
			$table->string('apple', 128)->nullable();
			$table->timestamp('email_verified_at')->nullable();
			$table->rememberToken();
			$table->timestamps();

			$table->foreign('role_id')->references('id')->on('roles')->nullOnDelete();
		});
	}
};
