<?php

namespace App\Http\Requests;

use App\Traits\CustomFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class SignInAuthRequest extends FormRequest
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
			'user_name' => 'required|string',
			'password' => 'required|string'
		];
	}
}
