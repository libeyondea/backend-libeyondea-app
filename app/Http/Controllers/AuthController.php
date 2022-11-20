<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Setting;
use App\Models\User;
use App\Traits\ApiResponser;
use App\Transformers\MeTransformer;
use App\Utils\Logger;
use Exception;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
	use ApiResponser;

	public function signIn(Request $request): JsonResponse
	{
		try {
			$credentials = $request->only(['user_name', 'password']);

			DB::beginTransaction();
			$user = new User($credentials);

			if ($user->isInvalidFor('SIGNIN')) {
				return $this->respondBadRequest(
					'The given data was invalid.',
					$user
						->validator()
						->errors()
						->messages()
				);
			}

			if (!auth()->attempt($credentials)) {
				return $this->respondBadRequest('Invalid credentials.', [
					'user_name' => 'User name or password is incorrect.',
					'password' => 'User name or password is incorrect.',
				]);
			}

			if (!auth()->user()->status) {
				return $this->respondForbidden('Your account has not been activated.');
			}

			/** @var \App\Models\User $user **/
			$user = auth()->user();
			$tokenResult = $user->createToken('Personal Access Token');
			DB::commit();

			return $this->respondSuccess([
				'user' => fractal($user, new MeTransformer())->toArray(),
				'token' => $tokenResult->plainTextToken,
			]);
		} catch (Exception $e) {
			DB::rollBack();
			Logger::emergency($e);
			return $this->respondInternalError($e->getMessage());
		}
	}

	public function signUp(Request $request): JsonResponse
	{
		try {
			$attrs = $request->all();

			DB::beginTransaction();
			$user = new User($attrs);

			if ($user->isInvalidFor('SIGNUP')) {
				return $this->respondBadRequest(
					'The given data was invalid.',
					$user
						->validator()
						->errors()
						->messages()
				);
			}

			$user->first_name = $attrs['first_name'];
			$user->last_name = $attrs['last_name'];
			$user->user_name = $attrs['user_name'];
			$user->email = $attrs['email'];
			$user->role = 'member';
			$user->status = false;
			$user->password = bcrypt($attrs['password']);
			$user->avatar = 'default-avatar.png';
			$user->save();

			Setting::create([
				'user_id' => $user->id,
				'theme' => 'light',
			]);
			DB::commit();

			return $this->respondSuccess(fractal($user, new MeTransformer())->toArray());
		} catch (Exception $e) {
			DB::rollBack();
			Logger::emergency($e);
			return $this->respondInternalError($e->getMessage());
		}
	}

	public function signOut(): JsonResponse
	{
		try {
			DB::beginTransaction();
			/** @var \App\Models\User $user **/
			$user = auth()->user();
			$user->tokens()->delete();
			DB::commit();

			return $this->respondSuccess();
		} catch (Exception $e) {
			DB::rollBack();
			Logger::emergency($e);
			return $this->respondInternalError($e->getMessage());
		}
	}

	public function me(): JsonResponse
	{
		try {
			$user = User::findOrFail(auth()->user()->id);

			return $this->respondSuccess(fractal($user, new MeTransformer())->toArray());
		} catch (Exception $e) {
			Logger::emergency($e);
			return $this->respondInternalError($e->getMessage());
		}
	}
}
