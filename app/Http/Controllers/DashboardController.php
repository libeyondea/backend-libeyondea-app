<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponsor;
use App\Utils\Logger;
use Exception;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
	use ApiResponsor;

	public function show(): JsonResponse
	{
		try {
			$dashboard = [
				'user' => [
					'total' => User::get()->count(),
				],
			];

			return $this->respondSuccess($dashboard);
		} catch (Exception $e) {
			Logger::emergency($e);
			return $this->respondInternalError($e->getMessage());
		}
	}
}
