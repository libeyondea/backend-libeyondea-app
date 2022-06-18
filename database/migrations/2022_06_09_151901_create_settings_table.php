<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('settings', function (Blueprint $table) {
			$table->id();
			$table
				->foreignIdFor(User::class)
				->unique()
				->constrained()
				->cascadeOnUpdate()
				->cascadeOnDelete();
			$table->enum('navbar', ['fixed', 'static'])->default('fixed');
			$table->enum('footer', ['fixed', 'static'])->default('static');
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
		Schema::dropIfExists('settings');
	}
}
