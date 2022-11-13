<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\CustomScope;
use App\Traits\ModelValidatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable, CustomScope, ModelValidatable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = ['first_name', 'last_name', 'user_name', 'email', 'password', 'role', 'status', 'avatar'];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = ['password', 'remember_token'];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
		'status' => 'boolean',
	];

	public function rules(): array
	{
		return [
			'*' => [],
			'CREATE' => [
				'first_name' => 'required|string|max:20',
				'last_name' => 'required|string|max:20',
				'user_name' => 'required|string|min:3|max:20|unique:users',
				'email' => 'required|string|email|max:255|unique:users',
				'password' => 'string|min:6|max:66',
				'role' => 'required|string|in:owner,admin,moderator,member',
				'status' => 'required|boolean',
				'avatar' => 'string|max:255',
			],
			'UPDATE' => [
				'first_name' => 'required|string|max:20',
				'last_name' => 'required|string|max:20',
				'user_name' => 'required|string|min:3|max:20|unique:users,user_name,' . $this->id,
				'email' => 'required|string|email|max:255|unique:users,email,' . $this->id,
				'password' => 'string|min:6|max:66',
				'role' => 'required|string|in:owner,admin,moderator,member',
				'status' => 'required|boolean',
				'avatar' => 'string|max:255',
			],
			'PROFILE' => [
				'first_name' => 'required|string|max:20',
				'last_name' => 'required|string|max:20',
				'user_name' => 'required|string|min:3|max:20|unique:users,user_name,' . $this->id,
				'email' => 'required|string|email|max:255|unique:users,email,' . $this->id,
				'password' => 'string|min:6|max:66',
				'avatar' => 'nullable|string|max:255',
			],
			'SIGNIN' => [
				'user_name' => 'required|string|min:3|max:20',
				'password' => 'required|string|min:6|max:66',
			],
			'SIGNUP' => [
				'first_name' => 'required|string|max:20',
				'last_name' => 'required|string|max:20',
				'user_name' => 'required|string|min:3|max:20|unique:users',
				'email' => 'required|string|email|max:255|unique:users',
				'password' => 'required|string|min:6|max:66',
			],
		];
	}

	public function getAvatarAttribute()
	{
		return config('app.img_url') . '/' . $this->attributes['avatar'];
	}

	public function setting()
	{
		return $this->hasOne(Setting::class);
	}
}
