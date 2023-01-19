<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\User;

class MeTransformer extends TransformerAbstract
{
	/**
	 * List of resources to automatically include
	 *
	 * @var array
	 */
	protected array $defaultIncludes = [
		//
	];

	/**
	 * List of resources possible to include
	 *
	 * @var array
	 */
	protected array $availableIncludes = [
		//
	];

	/**
	 * A Fractal transformer.
	 *
	 * @return array
	 */
	public function transform(User $user): array
	{
		return [
			'id' => $user->id,
			'avatar' => $user->avatar,
			'first_name' => $user->first_name,
			'last_name' => $user->last_name,
			'user_name' => $user->user_name,
			'email' => $user->email,
			'role' => $user->role,
			'token' => $user->token,
			'status' => $user->status,
			'last_sign_in' => $user->last_sign_in,
			'created_at' => $user->created_at,
			'updated_at' => $user->updated_at,
			'setting' => [
				'id' => $user->setting->id,
				'language' => $user->setting->language,
				'created_at' => $user->setting->created_at,
				'updated_at' => $user->setting->updated_at,
			],
		];
	}
}
