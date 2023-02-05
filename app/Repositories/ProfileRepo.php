<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\User;
use App\Transformers\ProfileTransformer;
use App\Utils\Logger;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProfileRepo extends AbstractBaseRepo
{
	public function show(): array
	{
		try {
			$user = User::where('id', auth()->user()->id)->first();

			if ($user) {
				$results = fractal($user, new ProfileTransformer())->toArray();

				return [
					'success' => true,
					'code' => Response::HTTP_OK,
					'message' => 'Get profile success.',
					'data' => $results['data'],
				];
			} else {
				return [
					'success' => false,
					'code' => Response::HTTP_NOT_FOUND,
					'message' => 'Profile not found.',
				];
			}
		} catch (Exception $e) {
			Logger::emergency($e);
			return [
				'success' => false,
				'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
				'message' => $e->getMessage(),
			];
		}
	}

	public function update(Request $request): array
	{
		try {
			$validator = Validator::make($request->all(), [
				'first_name' => 'required|string|max:20',
				'last_name' => 'required|string|max:20',
				'user_name' => 'required|string|min:3|max:20|unique:users,user_name,' . auth()->user()->id,
				'email' => 'required|string|email|max:255|unique:users,email,' . auth()->user()->id,
				'password' => 'string|min:6|max:60',
				'avatar' => 'string|max:255',
			]);

			if ($validator->fails()) {
				return [
					'success' => false,
					'code' => Response::HTTP_BAD_REQUEST,
					'message' => $validator->errors()->first(),
				];
			}

			$validatedData = $validator->validated();

			DB::beginTransaction();
			$user = User::where('id', auth()->user()->id)->first();

			if ($user) {
				$user->first_name = $validatedData['first_name'];
				$user->last_name = $validatedData['last_name'];
				$user->user_name = $validatedData['user_name'];
				$user->email = $validatedData['email'];

				if (isset($validatedData['password'])) {
					$user->password = $validatedData['password'];
				}

				if (isset($validatedData['avatar'])) {
					$user->avatar = $validatedData['avatar'];
				}

				$user->save();

				return [
					'success' => true,
					'code' => Response::HTTP_OK,
					'message' => 'Update profile success.',
				];
			} else {
				return [
					'success' => false,
					'code' => Response::HTTP_NOT_FOUND,
					'message' => 'Profile not found.',
				];
			}
			DB::commit();
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
}
