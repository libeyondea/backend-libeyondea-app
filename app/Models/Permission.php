<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
	use HasFactory;

	protected $fillable = ['role_id', 'module_id', 'view', 'create', 'update', 'delete'];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'view' => 'boolean',
		'create' => 'boolean',
		'update' => 'boolean',
		'delete' => 'boolean',
	];

	public function role()
	{
		return $this->belongsTo(Role::class);
	}

	public function module()
	{
		return $this->belongsTo(Module::class);
	}
}
