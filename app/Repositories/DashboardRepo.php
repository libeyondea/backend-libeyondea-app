<?php

namespace App\Repositories;

use App\Models\User;
use App\Utils\Logger;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DashboardRepo extends AbstractBaseRepo
{
	public function show(Request $request): array
	{
		try {
			$dashboard = [
				'user' => [
					'total' => User::get()->count(),
				],
			];

			return [
				'success' => true,
				'code' => Response::HTTP_OK,
				'message' => 'Get dashboard success.',
				'data' => $dashboard,
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
}
