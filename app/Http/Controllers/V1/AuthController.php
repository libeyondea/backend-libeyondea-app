<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Repositories\AuthRepo;

class AuthController extends Controller
{
	protected $authRepo;

	public function __construct(AuthRepo $authRepo)
	{
		$this->authRepo = $authRepo;
	}

	public function signIn(Request $request): JsonResponse
	{
		$results = $this->authRepo->signIn($request);
		return response()->json($results, $results['code']);
	}

	public function signUp(Request $request): JsonResponse
	{
		$results = $this->authRepo->signUp($request);
		return response()->json($results, $results['code']);
	}

	public function signOut(): JsonResponse
	{
		$results = $this->authRepo->signOut();
		return response()->json($results, $results['code']);
	}

	public function me(): JsonResponse
	{
		$results = $this->authRepo->me();
		return response()->json($results, $results['code']);
	}
}
