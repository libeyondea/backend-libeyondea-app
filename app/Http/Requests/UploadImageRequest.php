<?php

namespace App\Http\Requests;

use App\Traits\CustomFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
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
			'image' => 'required|image|mimes:jpeg,jpg,png,gif|max:2048',
		];
	}
}
