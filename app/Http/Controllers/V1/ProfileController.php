<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Repositories\ProfileRepo;

class ProfileController extends Controller
{
	protected $profileRepo;

	public function __construct(ProfileRepo $profileRepo)
	{
		$this->profileRepo = $profileRepo;
	}

	public function show(int $id): JsonResponse
	{
		$results = $this->profileRepo->show($id);
		return response()->json($results, $results['code']);
	}

	public function update(Request $request): JsonResponse
	{
		$results = $this->profileRepo->update($request);
		return response()->json($results, $results['code']);
	}
}
