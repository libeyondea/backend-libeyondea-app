<?php

namespace App\Models;

use App\Traits\ModelValidatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
	use HasFactory, ModelValidatable;

	protected $fillable = ['user_id', 'theme'];

	public function rules(): array
	{
		return [
			'*' => [],
			'UPDATE' => [
				'theme' => 'required|string|in:light,dark,retro,valentine,halloween,forest,dracula,night,coffee,winter',
			],
		];
	}
}
