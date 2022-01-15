<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$user = User::findOrFail($this->id);
		return [
			'first_name' => 'required|string|max:20',
			'last_name' => 'required|string|max:20',
			'user_name' => 'required|string|min:3|max:20|unique:users,user_name,' . $user->id,
			'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
			'password' => 'string|min:6|max:66',
			'avatar' => 'nullable|string|max:255',
			'role' => 'required|string|in:superadmin,admin,moderator,member',
			'status' => 'required|string|in:active,inactive,banned',
		];
	}
}
