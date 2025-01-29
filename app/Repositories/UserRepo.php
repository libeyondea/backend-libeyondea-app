<?php

namespace App\Repositories;

use App\Beans\ModuleType;
use App\Beans\PermissionType;
use App\Models\Setting;
use App\Models\User;
use App\Transformers\UserTransformer;
use App\Utils\Logger;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserRepo extends AbstractBaseRepo
{
	public function list(): array
	{
		try {
			if (!$this->isPermission(ModuleType::USER, PermissionType::VIEW)) {
				return $this->errorPermission();
			}

			$queryBuilder = User::query()->search()->filter();

			$users = $queryBuilder->pagination();

			$results = fractal($users, new UserTransformer())->toArray();

			return [
				'success' => true,
				'code' => Response::HTTP_OK,
				'message' => 'Get users success.',
				'data' => $results['data'],
				'columns' => UserTransformer::columns,
				'meta' => $results['meta'],
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

	public function show(int $id): array
	{
		try {
			$user = User::where('id', $id)->first();

			if ($user) {
				$results = fractal($user, new UserTransformer())->toArray();

				return [
					'success' => true,
					'code' => Response::HTTP_OK,
					'message' => 'Get user success.',
					'data' => $results['data'],
				];
			} else {
				return [
					'success' => false,
					'code' => Response::HTTP_NOT_FOUND,
					'message' => 'User not found.',
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

	public function store(Request $request): array
	{
		try {
			$validator = Validator::make($request->all(), [
				'first_name' => 'required|string|max:20',
				'last_name' => 'required|string|max:20',
				'user_name' => 'required|string|min:3|max:20|unique:users',
				'email' => 'required|string|email|max:255|unique:users',
				'password' => 'string|min:6|max:60',
				'role' => 'required',
				'role.id' => 'required|integer',
				'status' => 'required|integer',
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
			$user = new User();
			$user->first_name = $validatedData['first_name'];
			$user->last_name = $validatedData['last_name'];
			$user->user_name = $validatedData['user_name'];
			$user->email = $validatedData['email'];
			$user->role_id = $validatedData['role']['id'];
			$user->status = $validatedData['status'];
			$user->token = null;
			$user->last_sign_in = null;

			if (isset($request->password)) {
				$user->password = bcrypt($validatedData['password']);
			} else {
				$user->password = bcrypt(Str::random(10));
			}

			if (isset($request->avatar)) {
				$user->avatar = $validatedData['avatar'];
			} else {
				$user->avatar = 'default-avatar.png';
			}

			$user->save();

			$setting = new Setting();
			$setting->user_id = $user->id;
			$setting->language = 'en';
			$setting->save();
			DB::commit();

			return [
				'success' => true,
				'code' => Response::HTTP_OK,
				'message' => 'Create user success.',
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

	public function update(int $id, Request $request): array
	{
		try {
			$validator = Validator::make($request->all(), [
				'first_name' => 'required|string|max:20',
				'last_name' => 'required|string|max:20',
				'user_name' => 'required|string|min:3|max:20|unique:users,user_name,' . $id,
				'email' => 'required|string|email|max:255|unique:users,email,' . $id,
				'password' => 'string|min:6|max:60',
				'role' => 'required',
				'role.id' => 'required|integer',
				'status' => 'required|integer',
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
			$user = User::where('id', $id)->first();

			if ($user) {
				if ($user->id === auth()->user()->id) {
					return [
						'success' => false,
						'code' => Response::HTTP_FORBIDDEN,
						'message' => 'You cannot update your own profile.',
					];
				}

				$user->first_name = $validatedData['first_name'];
				$user->last_name = $validatedData['last_name'];
				$user->user_name = $validatedData['user_name'];
				$user->email = $validatedData['email'];
				$user->role_id = $validatedData['role']['id'];
				$user->status = $validatedData['status'];
				$user->token = null;
				$user->last_sign_in = null;

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
					'message' => 'Update user success.',
				];
			} else {
				return [
					'success' => false,
					'code' => Response::HTTP_NOT_FOUND,
					'message' => 'User not found.',
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

	public function destroy(int $id): array
	{
		try {
			DB::beginTransaction();
			$user = User::where('id', $id)->first();

			if ($user) {
				if ($user->id === auth()->user()->id) {
					return [
						'success' => false,
						'code' => Response::HTTP_FORBIDDEN,
						'message' => 'You cannot delete your own profile.',
					];
				}

				$user->delete();

				return [
					'success' => true,
					'code' => Response::HTTP_OK,
					'message' => 'Delete user success.',
				];
			} else {
				return [
					'success' => false,
					'code' => Response::HTTP_NOT_FOUND,
					'message' => 'User not found.',
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
