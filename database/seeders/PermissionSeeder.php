<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class PermissionSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$json = Storage::disk()->get('data/permissions.json');
		$permissions = json_decode($json);

		foreach ($permissions as $permission) {
			Permission::create([
				'id' => $permission->id,
				'role_id' => $permission->role_id,
				'module_id' => $permission->module_id,
				'view' => $permission->view,
				'create' => $permission->create,
				'update' => $permission->update,
				'delete' => $permission->delete,
			]);
		}
	}
}
