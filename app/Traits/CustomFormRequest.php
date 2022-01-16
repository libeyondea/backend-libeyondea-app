<?php

namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

trait CustomFormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Handle a failed validation attempt.
	 *
	 * @param  \Illuminate\Contracts\Validation\Validator  $validator
	 * @return void
	 *
	 * @throws \Illuminate\Validation\ValidationException
	 */
	protected function failedValidation(Validator $validator)
	{
		throw (new ValidationException($validator))
			->errorBag($this->errorBag)
			->redirectTo($this->getRedirectUrl())
			->status(400);
	}
}
