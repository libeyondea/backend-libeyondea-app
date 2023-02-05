<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Repositories\DashboardRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
	protected $dashboardRepo;

	public function __construct(DashboardRepo $dashboardRepo)
	{
		$this->dashboardRepo = $dashboardRepo;
	}

	public function show(Request $request): JsonResponse
	{
		$results = $this->dashboardRepo->show($request);
		return response()->json($results, $results['code']);
	}
}
