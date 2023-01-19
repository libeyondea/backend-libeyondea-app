<?php

namespace App\Utils;

use Carbon\Carbon;

class Utils
{
	public static function getSystemCurrentDateTime()
	{
		$res = Carbon::now();
		return $res;
	}
}
