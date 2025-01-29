<?php

use App\Exceptions\Handler;
use App\Http\Middleware\StatusCheck;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
	->withRouting(
		api: __DIR__ . '/../routes/api.php',
		apiPrefix: 'api/v1',
		web: __DIR__ . '/../routes/web.php',
		commands: __DIR__ . '/../routes/console.php',
		health: '/up'
	)
	->withMiddleware(function (Middleware $middleware) {
		$middleware->alias([
			'status' => StatusCheck::class,
		]);
	})
	->create();

$app->singleton(\Illuminate\Contracts\Debug\ExceptionHandler::class, Handler::class);

$app->afterResolving(
	Handler::class,
	fn($handler) => (function (Exceptions $exceptions) {
		//
	})(new Exceptions($handler))
);

return $app;
