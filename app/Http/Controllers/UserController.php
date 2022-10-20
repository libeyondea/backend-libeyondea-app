<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Setting;
use App\Models\User;
use App\Traits\ApiResponser;

class UserController extends Controller
{
	use ApiResponser;

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index(Request $request)
	{
		$users = new User();
		if ($request->filled('search')) {
			$users = $users->where(DB::raw('CONCAT_WS(" ", first_name, last_name)'), 'LIKE', '%' . $request->search . '%')
				->orWhere(DB::raw('CONCAT_WS(" ", last_name, first_name)'), 'LIKE', '%' . $request->search . '%')
				->orWhere('user_name', 'LIKE', '%' . $request->search . '%')
				->orWhere('email', 'LIKE', '%' . $request->search . '%');
		}
		$usersCount = $users->get()->count();
		$users = $users->pagination();

		return $this->respondSuccessWithPagination(new UserCollection($users), $usersCount);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function show($id)
	{
		$user = User::findOrFail($id);

		return $this->respondSuccess(new UserResource($user));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \App\Http\Requests\StoreUserRequest  $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function store(StoreUserRequest $request)
	{
		$userData = $request->all();
		$user = User::create($userData);
		Setting::create([
			'user_id' => $user->id,
			'theme' => 'light'
		]);

		return $this->respondSuccess(new UserResource($user));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \App\Http\Requests\UpdateUserRequest  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update(UpdateUserRequest $request, $id)
	{
		$userData = $request->all();
		$user = User::findOrFail($id);
		if (auth()->user()->id === $user->id) {
			return $this->respondForbidden('You cannot update your own profile.');
		}
		$user->update($userData);

		return $this->respondSuccess(new UserResource($user));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function destroy($id)
	{
		$user = User::findOrFail($id);
		if (auth()->user()->id === $user->id) {
			return $this->respondForbidden('You cannot delete your own profile.');
		}
		$user->delete();

		return $this->respondSuccess(new UserResource($user));
	}
}
