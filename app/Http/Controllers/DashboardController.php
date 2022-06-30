<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponser;

class DashboardController extends Controller
{
	use ApiResponser;

	public function show()
	{
		$dashboard = [
			'user' => [
				'total' => User::get()->count(),
			]
		];

		return $this->respondSuccess($dashboard);
	}
}
