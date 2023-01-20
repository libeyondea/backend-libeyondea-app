<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ModuleSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$json = Storage::disk()->get('data/modules.json');
		$modules = json_decode($json);

		foreach ($modules as $module) {
			Module::create([
				'id' => $module->id,
				'name' => $module->name,
			]);
		}
	}
}
