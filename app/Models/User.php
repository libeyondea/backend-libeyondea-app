<?php

namespace App\Models;

use App\Traits\PaginationScope;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable, PaginationScope;

	/**
	 * The "booted" method of the model.
	 *
	 * @return void
	 */
	protected static function booted()
	{
		static::creating(function ($user) {
			$user->password = bcrypt($user->password);
		});
		static::updating(function ($user) {
			if ($user->password) {
				$user->password = bcrypt($user->password);
			}
		});
	}

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'first_name',
		'last_name',
		'user_name',
		'avatar',
		'email',
		'password',
		'role',
		'status',
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	public function getAvatarUrlAttribute()
	{
		return $this->avatar ? config('app.img_url') . '/' . $this->avatar : config('app.url') . '/' . 'default-avatar.png';
	}
}
