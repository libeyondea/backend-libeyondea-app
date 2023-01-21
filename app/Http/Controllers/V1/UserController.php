<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponsor;
use App\Repositories\UserRepo;

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
		$results = $this->userRepo->show($id);
		return response()->json($results, $results['code']);
	}

	public function store(Request $request): JsonResponse
	{
		$results = $this->userRepo->store($request);
		return response()->json($results, $results['code']);
	}

	public function update(int $id, Request $request): JsonResponse
	{
		$results = $this->userRepo->update($id, $request);
		return response()->json($results, $results['code']);
	}

	public function destroy(int $id): JsonResponse
	{
		$results = $this->userRepo->destroy($id);
		return response()->json($results, $results['code']);
	}
}
