<?php

namespace App\Repositories;

use App\Beans\RoleType;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use App\Transformers\MeTransformer;
use App\Utils\Logger;
use App\Utils\Utils;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthRepo extends AbstractBaseRepo
{
	public function signIn(Request $request): array
	{
		try {
			$validator = Validator::make($request->all(), [
				'user_name' => 'required',
				'password' => 'required',
			]);

			if ($validator->fails()) {
				return [
					'success' => false,
					'code' => Response::HTTP_BAD_REQUEST,
					'message' => $validator->errors()->first(),
				];
			}

			$credentials = $request->only(['user_name', 'password']);

			if (!auth()->attempt($credentials)) {
				return [
					'success' => false,
					'code' => Response::HTTP_BAD_REQUEST,
					'message' => 'User_name or password is incorrect.',
				];
			}

			/** @var \App\Models\User $user **/
			$user = auth()->user();

			if ($user->status === 1) {
				$tokenResult = $user->createToken('Personal Access Token');

				DB::beginTransaction();
				$user->token = $tokenResult->plainTextToken;
				$user->last_sign_in = Utils::getSystemCurrentDateTime();
				$user->save();
				DB::commit();

				$results = fractal($user, new MeTransformer())->toArray();

				return [
					'success' => true,
					'code' => Response::HTTP_OK,
					'message' => 'Sign in success.',
					'data' => [
			        'user' => $results,
	            'token' => $user->token
					],
				];
			} elseif ($user->status === 0) {
				return [
					'success' => false,
					'code' => Response::HTTP_FORBIDDEN,
					'message' => 'Your account has not been activated.',
				];
			} elseif ($user->status === 2) {
				return [
					'success' => false,
					'code' => Response::HTTP_FORBIDDEN,
					'message' => 'Your account has been blocked.',
				];
			} else {
				return [
					'success' => false,
					'code' => Response::HTTP_FORBIDDEN,
					'message' => 'Your account is inaccessible.',
				];
			}
		} catch (Exception $e) {
			DB::rollBack();
			Logger::emergency($e);
			return [
				'success' => false,
				'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
				'message' => $e->getMessage(),
			];
		}
	}

	public function signUp(Request $request): array
	{
		try {
			$validator = Validator::make($request->all(), [
				'first_name' => 'required|string|max:20',
				'last_name' => 'required|string|max:20',
				'user_name' => 'required|string|min:3|max:20|unique:users',
				'email' => 'required|string|email|max:255|unique:users',
				'password' => 'required|string|min:6|max:60',
			]);

			if ($validator->fails()) {
				return [
					'success' => false,
					'code' => Response::HTTP_BAD_REQUEST,
					'message' => $validator->errors()->first(),
				];
			}

			DB::beginTransaction();
			$user = new User();
			$user->first_name = $request->first_name;
			$user->last_name = $request->last_name;
			$user->user_name = $request->user_name;
			$user->email = $request->email;
			$user->avatar = 'default-avatar.png';
			$user->password = bcrypt($request->password);
			$user->role_id = Role::where('code', RoleType::MEMBER)->first()->id;
			$user->token = null;
			$user->status = 0;
			$user->last_sign_in = null;
			$user->save();

			$setting = new Setting();
			$setting->user_id = $user->id;
			$setting->language = 'en';
			$setting->save();
			DB::commit();

			$results = fractal($user, new MeTransformer())->toArray();

			return [
				'success' => true,
				'code' => Response::HTTP_OK,
				'message' => 'Sign up success.',
				'data' => [
				    'user' => $results,
					  'token' => $user->token
				],
			];
		} catch (Exception $e) {
			DB::rollBack();
			Logger::emergency($e);
			return [
				'success' => false,
				'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
				'message' => $e->getMessage(),
			];
		}
	}

	public function signOut(): array
	{
		try {
			DB::beginTransaction();
			/** @var \App\Models\User $user **/
			$user = auth()->user();
			$user->tokens()->delete();
			DB::commit();

			return [
				'success' => true,
				'code' => Response::HTTP_OK,
				'message' => 'Sign out success.',
			];
		} catch (Exception $e) {
			DB::rollBack();
			Logger::emergency($e);
			return [
				'success' => false,
				'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
				'message' => $e->getMessage(),
			];
		}
	}

	public function me(): array
	{
		try {
			$user = auth()->user();

			$results = fractal($user, new MeTransformer())->toArray();

			return [
				'success' => true,
				'code' => Response::HTTP_OK,
				'message' => 'Get me success.',
				'data' => [
				    'user' => $results,
	          'token' => $user->token
				],
			];
		} catch (Exception $e) {
			Logger::emergency($e);
			return [
				'success' => false,
				'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
				'message' => $e->getMessage(),
			];
		}
	}
}
