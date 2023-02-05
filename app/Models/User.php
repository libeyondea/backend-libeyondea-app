<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\CustomScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable, CustomScope;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = ['role_id', 'first_name', 'last_name', 'user_name', 'email', 'avatar', 'token', 'status', 'last_sign_in'];

	/**
	 * The fields that should be filterable by query.
	 *
	 * @var array
	 */
	protected $filterable = ['first_name', 'last_name', 'user_name', 'email', 'created_at', 'updated_at'];

	/**
	 * The fields that should be sortable by query.
	 *
	 * @var array
	 */
	protected $sortable = ['first_name', 'last_name', 'user_name', 'email', 'created_at', 'updated_at'];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = ['password'];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	public function getAvatarAttribute()
	{
		return config('app.image_url') . '/' . $this->attributes['avatar'];
	}

	public function setting()
	{
		return $this->hasOne(Setting::class);
	}

	public function role()
	{
		return $this->belongsTo(Role::class);
	}
}
