<?php

use App\Models\Module;
use App\Models\Role;
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
		Schema::create('permissions', function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Role::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
			$table->foreignIdFor(Module::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
			$table->unique(['role_id', 'module_id']);
			$table->boolean('view')->default(false);
			$table->boolean('create')->default(false);
			$table->boolean('update')->default(false);
			$table->boolean('delete')->default(false);
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
		Schema::dropIfExists('permissions');
	}
};
