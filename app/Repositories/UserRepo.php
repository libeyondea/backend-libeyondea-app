<?php

namespace App\Repositories;

use App\Beans\ModuleType;
use App\Beans\PermissionType;
use App\Models\User;
use App\Transformers\UserTransformer;
use App\Utils\Logger;
use App\Utils\Utils;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserRepo extends AbstractBaseRepo
{
	public function list(Request $request): array
	{
		try {
			if (!$this->isPermission(ModuleType::USER, PermissionType::VIEW)) {
				return $this->errorPermission();
			}

			$queryBuilder = User::query();

			$queryBuilder->searchCriteriaInQueryBuilder(['first_name', 'last_name', 'user_name', 'email']);

			$queryBuilder = $queryBuilder->pagination();

			$results = fractal($queryBuilder, new UserTransformer())->toArray();

			return [
				'success' => true,
				'code' => Response::HTTP_OK,
				'message' => 'Get users success.',
				'data' => $results['data'],
				'columns' => UserTransformer::columns,
				'meta' => $results['meta'],
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
