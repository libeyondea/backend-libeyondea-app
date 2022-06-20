<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
	use HasFactory;

	protected $fillable = [
		'user_id',
		'fixed_navbar',
		'fixed_footer',
	];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'fixed_navbar' => 'boolean',
		'fixed_footer' => 'boolean',
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
