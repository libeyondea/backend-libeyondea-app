<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\ApiResponser;
use App\Transformers\ProfileTransformer;
use App\Utils\Logger;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
	use ApiResponser;

	public function show(): JsonResponse
	{
		try {
			$user = User::findOrFail(auth()->user()->id);

			return $this->respondSuccess(fractal($user, new ProfileTransformer())->toArray());
		} catch (Exception $e) {
			Logger::emergency($e);
			return $this->respondError($e->getMessage());
		}
	}

	public function update(Request $request): JsonResponse
	{
		try {
			$attrs = $request->all();

			DB::beginTransaction();
			$user = User::findOrFail(auth()->user()->id);

			if ($user->isInvalidFor('PROFILE')) {
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

			if (isset($attrs['password'])) {
				$user->password = $attrs['password'];
			}

			if (isset($attrs['avatar'])) {
				$user->avatar = $attrs['avatar'];
			}

			$user->save();
			DB::commit();

			return $this->respondSuccess(fractal($user, new ProfileTransformer())->toArray());
		} catch (Exception $e) {
			DB::rollBack();
			Logger::emergency($e);
			return $this->respondError($e->getMessage());
		}
	}
}
