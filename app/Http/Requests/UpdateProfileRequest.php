<?php

namespace App\Http\Requests;

use App\Traits\CustomFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
	use CustomFormRequest;

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'first_name' => 'required|string|max:20',
			'last_name' => 'required|string|max:20',
			'user_name' => 'required|string|min:3|max:20|unique:users,user_name,' . auth()->user()->id,
			'email' => 'required|string|email|max:255|unique:users,email,' . auth()->user()->id,
			'password' => 'string|min:6|max:66',
			'avatar' => 'nullable|string|max:255'
		];
	}
}
