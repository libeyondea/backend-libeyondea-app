<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use App\Models\Setting;
use App\Models\User;
use App\Repositories\UserRepo;
use App\Traits\ApiResponsor;
use App\Transformers\UserTransformer;
use App\Utils\Logger;
use Exception;

class UserController extends Controller
{
	use ApiResponsor;

	protected $userRepo;

	public function __construct(UserRepo $userRepo)
	{
		$this->userRepo = $userRepo;
	}

	public function index(Request $request): JsonResponse
	{
		$results = $this->userRepo->list($request);
		return response()->json($results, $results['code']);
	}

	public function show(int $id): JsonResponse
	{
		try {
			$user = User::findOrFail($id);
			return $this->respondSuccess(fractal($user, new UserTransformer())->toArray());
		} catch (Exception $e) {
			Logger::emergency($e);
			return $this->respondInternalError($e->getMessage());
		}
	}

	public function store(Request $request): JsonResponse
	{
		try {
			$attrs = $request->all();

			DB::beginTransaction();
			$user = new User($attrs);

			if ($user->isInvalidFor('CREATE')) {
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
			$user->role = $attrs['role'];
			$user->status = $attrs['status'];
			$user->password = isset($attrs['password']) ? bcrypt($attrs['password']) : bcrypt(Str::random(10));
			$user->avatar = isset($attrs['avatar']) ? $attrs['avatar'] : 'default-avatar.png';
			$user->save();

			Setting::create([
				'user_id' => $user->id,
				'theme' => 'light',
			]);
			DB::commit();

			return $this->respondSuccess(fractal($user, new UserTransformer())->toArray());
		} catch (Exception $e) {
			DB::rollBack();
			Logger::emergency($e);
			return $this->respondInternalError($e->getMessage());
		}
	}

	public function update(int $id, Request $request): JsonResponse
	{
		try {
			$attrs = $request->all();

			$user = User::findOrFail($id);

			if (auth()->user()->id === $user->id) {
				return $this->respondForbidden('You cannot update your own profile.');
			}

			if ($user->isInvalidFor('UPDATE')) {
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
			$user->role = $attrs['role'];
			$user->status = $attrs['status'];
			if (isset($attrs['password'])) {
				$user->password = $attrs['password'];
			}
			if (isset($attrs['avatar'])) {
				$user->avatar = $attrs['avatar'];
			}
			$user->save();

			return $this->respondSuccess(fractal($user, new UserTransformer())->toArray());
		} catch (Exception $e) {
			DB::rollBack();
			Logger::emergency($e);
			return $this->respondInternalError($e->getMessage());
		}
	}

	public function destroy(int $id): JsonResponse
	{
		try {
			DB::beginTransaction();
			$user = User::findOrFail($id);
			if (auth()->user()->id === $user->id) {
				return $this->respondForbidden('You cannot delete your own profile.');
			}
			$user->delete();
			DB::commit();

			return $this->respondSuccess(fractal($user, new UserTransformer())->toArray());
		} catch (Exception $e) {
			DB::rollBack();
			Logger::emergency($e);
			return $this->respondInternalError($e->getMessage());
		}
	}
}
