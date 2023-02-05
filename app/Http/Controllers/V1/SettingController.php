<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Repositories\SettingRepo;

class SettingController extends Controller
{
	protected $settingRepo;

	public function __construct(SettingRepo $settingRepo)
	{
		$this->settingRepo = $settingRepo;
	}

	public function show(): JsonResponse
	{
		$results = $this->settingRepo->show();
		return response()->json($results, $results['code']);
	}

	public function update(Request $request): JsonResponse
	{
		$results = $this->settingRepo->update($request);
		return response()->json($results, $results['code']);
	}
}
