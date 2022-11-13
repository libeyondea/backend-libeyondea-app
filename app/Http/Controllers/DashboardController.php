<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponser;
use App\Utils\Logger;
use Exception;

class DashboardController extends Controller
{
	use ApiResponser;

	public function show()
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
			return $this->respondError($e->getMessage());
		}
	}
}
