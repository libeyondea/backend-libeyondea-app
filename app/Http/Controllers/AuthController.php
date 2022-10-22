<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignInAuthRequest;
use App\Http\Requests\SignUpAuthRequest;
use App\Http\Resources\MeResource;
use App\Models\Setting;
use App\Models\User;
use App\Traits\ApiResponser;

class AuthController extends Controller
{
	use ApiResponser;

	public function signIn(SignInAuthRequest $request)
	{
		$credentials = $request->only(['user_name', 'password']);

		if (!auth()->attempt($credentials)) {
			return $this->respondBadRequest('Invalid credentials.', [
				'user_name' => 'User name or password is incorrect.',
				'password' => 'User name or password is incorrect.'
			]);
		} else if (!auth()->user()->status) {
			return $this->respondForbidden('Your account has not been activated.');
		}

		/** @var \App\Models\User $user **/
		$user = auth()->user();
		$tokenResult = $user->createToken('Personal Access Token');

		return $this->respondSuccess([
			'user' => new MeResource($user),
			'token' => $tokenResult->plainTextToken
		]);
	}

	public function signUp(SignUpAuthRequest $request)
	{
		$userData = $request->merge(['role' => 'member', 'avatar' => null, 'status' => false])->all();
		$user = User::create($userData);
		Setting::create([
			'user_id' => $user->id,
			'theme' => 'light'
		]);

		return $this->respondSuccess(new MeResource($user));
	}

	public function signOut()
	{
		/** @var \App\Models\User $user **/
		$user = auth()->user();
		$user->tokens()->delete();

		return $this->respondSuccess();
	}

	public function me()
	{
		$user = User::findOrFail(auth()->user()->id);

		return $this->respondSuccess(new MeResource($user));
	}
}
