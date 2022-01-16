<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignupRequest;
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
			return $this->respondBadRequest('Invalid credentials');
		}

		$tokenResult = auth()->user()->createToken('Personal Access Token');

		return $this->respondSuccess([
			'token' => $tokenResult->plainTextToken
		]);
	}

	public function signup(SignupRequest $request)
	{
		$userData = $request->merge(['role' => 'member', 'status' => 'inactive', 'avatar' => null])->all();
		$user = User::create($userData);
		return $this->respondSuccess(new UserResource($user));
	}

	public function signout()
	{
		auth()->user()->tokens()->delete();
		return $this->respondSuccess();
	}

	public function me()
	{
		$user = User::findOrFail(auth()->user()->id);
		return $this->respondSuccess(new UserResource($user));
	}
}
