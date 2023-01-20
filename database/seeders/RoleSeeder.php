<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class RoleSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$json = Storage::disk()->get('data/roles.json');
		$roles = json_decode($json);

		foreach ($roles as $role) {
			Role::create([
				'id' => $role->id,
				'name' => $role->name,
			]);
		}
	}
}
