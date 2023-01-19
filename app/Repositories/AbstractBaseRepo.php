<?php

namespace App\Repositories;

use App\Models\User;

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
}
