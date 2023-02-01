<?php

namespace App\Repositories;

use App\Beans\PermissionType;
use App\Beans\RoleType;
use App\Models\Permission;
use Illuminate\Http\Response;

abstract class AbstractBaseRepo
{
	public function isPermission($modules, $permit): bool
	{
		if (auth()->user()->role->code === RoleType::OWNER) {
			return true;
		} else {
			$modules = explode(',', $modules);
			foreach ($modules as $module) {
				$permission = Permission::where('role_id', auth()->user()->role_id)
					->whereHas('module', function ($q) use ($module) {
						$q->where('code', $module);
					})
					->first();
				if ($permission) {
					if ($permit === PermissionType::VIEW && $permission->view === true) {
						return true;
					} elseif ($permit === PermissionType::CREATE && $permission->create === true) {
						return true;
					} elseif ($permit === PermissionType::UPDATE && $permission->update === true) {
						return true;
					} elseif ($permit === PermissionType::DELETE && $permission->delete === true) {
						return true;
					}
				}
				return false;
			}
		}
		return false;
	}

	public function errorPermission()
	{
		return [
			'success' => false,
			'code' => Response::HTTP_FORBIDDEN,
			'message' => 'You have been denied permission to access this function.',
		];
	}
}
