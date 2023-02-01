<?php

namespace App\Repositories;

use App\Beans\ModuleType;
use App\Beans\PermissionType;
use App\Models\Setting;
use App\Models\User;
use App\Transformers\UserTransformer;
use App\Utils\Logger;
use App\Utils\Utils;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserRepo extends AbstractBaseRepo
{
	public function list(Request $request): array
	{
		try {
			if (!$this->isPermission(ModuleType::USER, PermissionType::VIEW)) {
				return $this->errorPermission();
			}

			$queryBuilder = User::query();

			$queryBuilder->searchCriteriaInQueryBuilder(['first_name', 'last_name', 'user_name', 'email']);

			$queryBuilder = $queryBuilder->pagination();

			$results = fractal($queryBuilder, new UserTransformer())->toArray();

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
			$queryBuilder = User::findOrFail($id);

			$results = fractal($queryBuilder, new UserTransformer())->toArray();

			return [
				'success' => true,
				'code' => Response::HTTP_OK,
				'message' => 'Get user success.',
				'data' => $results['data'],
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
				'role.name' => 'required|string',
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

			DB::beginTransaction();
			$user = new User();
			$user->first_name = $request->first_name;
			$user->last_name = $request->last_name;
			$user->user_name = $request->user_name;
			$user->email = $request->email;
			$user->role_id = $request->role['id'];
			$user->status = $request->status;
			$user->password = isset($request->password) ? bcrypt($request->password) : bcrypt(Str::random(10));
			$user->avatar = isset($request->avatar) ? $request->avatar : 'default-avatar.png';
			$user->token = null;
			$user->status = 0;
			$user->last_sign_in = null;
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
				'role.name' => 'required|string',
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

			$user = User::findOrFail($id);

			if (auth()->user()->id === $user->id) {
				return [
					'success' => false,
					'code' => Response::HTTP_FORBIDDEN,
					'message' => 'You cannot update your own profile.',
				];
			}

			$user->first_name = $request->first_name;
			$user->last_name = $request->last_name;
			$user->user_name = $request->user_name;
			$user->email = $request->email;
			$user->role_id = $request->role['id'];
			$user->status = $request->status;
			$user->token = null;
			$user->status = 0;
			$user->last_sign_in = null;

			if (isset($request->password)) {
				$user->password = $request->password;
			}

			if (isset($request->avatar)) {
				$user->avatar = $request->avatar;
			}

			$user->save();

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

	public function destroy(int $id): array
	{
		try {
			DB::beginTransaction();
			$user = User::findOrFail($id);

			if (auth()->user()->id === $user->id) {
				return [
					'success' => false,
					'code' => Response::HTTP_FORBIDDEN,
					'message' => 'You cannot delete your own profile.',
				];
			}

			$user->delete();
			DB::commit();

			return [
				'success' => true,
				'code' => Response::HTTP_OK,
				'message' => 'Delete user success.',
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
}
