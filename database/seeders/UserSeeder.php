<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		User::create([
			'first_name' => 'Thuc',
			'last_name' => 'Nguyen',
			'user_name' => 'libeyondea',
			'email' => 'libeyondea@gmail.com',
			'password' => 'libeyondea',
			'role' => 'owner',
			'actived' => true,
		]);
		User::factory(50)->create();
	}
}
