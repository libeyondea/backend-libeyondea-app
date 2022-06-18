<?php

namespace App\Http\Requests;

use App\Traits\CustomFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
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
			'navbar' => 'required|string|in:fixed,static',
			'footer' => 'required|string|in:fixed,static',
		];
	}
}
