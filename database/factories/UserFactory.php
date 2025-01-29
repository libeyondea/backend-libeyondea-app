<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
	/**
	 * The current password being used by the factory.
	 */
	protected static ?string $password;

	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'role_id' => 4,
			'first_name' => fake()->firstName(),
			'last_name' => fake()->lastName(),
			'user_name' => fake()->unique()->userName(),
			'email' => fake()->unique()->safeEmail(),
			'password' => bcrypt('password'),
			'avatar' => 'default-avatar.png',
		];
	}

	/**
	 * Indicate that the model's email address should be unverified.
	 */
	public function unverified(): static
	{
		return $this->state(
			fn(array $attributes) => [
				'email_verified_at' => null,
			]
		);
	}
}
