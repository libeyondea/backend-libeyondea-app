<?php

namespace App\Utils;

use Exception;
use Illuminate\Support\Facades\Log;

class Logger
{
	public static function emergency(Exception $e): void
	{
		Log::emergency('Message: ' . $e->getMessage() . ' File: ' . $e->getFile() . ' Line: ' . $e->getLine());
	}

	public static function alert(string $message): void
	{
		Log::alert($message);
	}

	public static function critical(string $message): void
	{
		Log::critical($message);
	}

	public static function error(string $message): void
	{
		Log::error($message);
	}

	public static function warning(string $message): void
	{
		Log::warning($message);
	}

	public static function notice(string $message): void
	{
		Log::notice($message);
	}

	public static function info(string $message): void
	{
		Log::info($message);
	}

	public static function debug(string $message): void
	{
		Log::debug($message);
	}
}
