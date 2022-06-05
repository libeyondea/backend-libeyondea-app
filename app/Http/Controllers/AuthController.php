<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignupAuthRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class AuthController extends Controller
{
	use ApiResponser;

	public function signin(Request $request)
	{
		$credentials = $request->only(['user_name', 'password']);

		if (!auth()->attempt($credentials)) {
			return $this->respondBadRequest('Invalid credentials.', [
				'user_name' => 'User name or password is incorrect.',
				'password' => 'User name or password is incorrect.'
			]);
		} else if (auth()->user()->status !== 'active') {
			return $this->respondForbidden('User is not active.');
		}

		/** @var \App\Models\User $user **/
		$user = auth()->user();
		$tokenResult = $user->createToken('Personal Access Token');

		return $this->respondSuccess([
			'token' => $tokenResult->plainTextToken
		]);
	}

	public function signup(SignupAuthRequest $request)
	{
		$userData = $request->merge(['role' => 'member', 'status' => 'inactive', 'avatar' => null])->all();
		$user = User::create($userData);
		return $this->respondSuccess(new UserResource($user));
	}

	public function signout()
	{
		/** @var \App\Models\User $user **/
		$user = auth()->user();
		$user->tokens()->delete();
		return $this->respondSuccess();
	}

	public function me()
	{
		$user = User::findOrFail(auth()->user()->id);
		return $this->respondSuccess(new UserResource($user));
	}
}
