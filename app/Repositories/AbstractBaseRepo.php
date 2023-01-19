<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Response;

abstract class AbstractBaseRepo
{
	public $loggedInUser;

	public function __construct()
	{
		$this->loggedInUser = $this->getLoggedInUser();
	}

	protected function getLoggedInUser()
	{
		$user = auth()->user();
		if ($user instanceof User) {
			return $user;
		} else {
			return new User();
		}
	}

	public function isPermission($module_codes, $permit): bool
	{
		return false;
	}

	public function isActive(): bool
	{
		if ($this->loggedInUser->status === 1) {
			return true;
		}
		return false;
	}

	public function isInactive(): bool
	{
		if ($this->loggedInUser->status === 0) {
			return true;
		}
		return false;
	}

	public function isBlocked(): bool
	{
		if ($this->loggedInUser->status === 2) {
			return true;
		}
		return false;
	}

	public function errorActive(): array
	{
		return [
			'success' => false,
			'code' => Response::HTTP_FORBIDDEN,
			'message' => 'Your account has not been activated.',
		];
	}
}
