<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->id();
			$table->string('first_name');
			$table->string('last_name');
			$table->string('user_name')->unique();
			$table->string('email')->unique();
			$table->string('avatar');
			$table->string('password');
			$table->enum('role', ['owner', 'admin', 'moderator', 'member'])->default('member');
			$table
				->string('token', 64)
				->unique()
				->nullable();
			$table->tinyInteger('status')->default(0);
			$table->timestamp('last_sign_in')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('users');
	}
};
