<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
			'role_id' => 1,
			'first_name' => 'Thuc',
			'last_name' => 'Nguyen',
			'user_name' => 'libeyondea',
			'email' => 'libeyondea@gmail.com',
			'password' => bcrypt('libeyondea'),
			'status' => 1,
			'avatar' => 'default-avatar.png',
		]);
		User::factory(50)->create();
	}
}
