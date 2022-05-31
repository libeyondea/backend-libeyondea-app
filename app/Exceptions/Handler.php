<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Arr;
use Throwable;

class Handler extends ExceptionHandler
{
	/**
	 * A list of the exception types that are not reported.
	 *
	 * @var array<int, class-string<Throwable>>
	 */
	protected $dontReport = [
		//
	];

	/**
	 * A list of the inputs that are never flashed for validation exceptions.
	 *
	 * @var array<int, string>
	 */
	protected $dontFlash = [
		'current_password',
		'password',
		'password_confirmation',
	];

	/**
	 * Register the exception handling callbacks for the application.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->reportable(function (Throwable $e) {
			//
		});
	}

	/**
	 * Convert an authentication exception into a response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Illuminate\Auth\AuthenticationException  $exception
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	protected function unauthenticated($request, AuthenticationException $exception)
	{
		return $this->shouldReturnJson($request, $exception)
			? response()->json(['message' => $exception->getMessage(), 'errors' => null], 401)
			: redirect()->guest($exception->redirectTo() ?? route('login'));
	}

	/**
	 * Convert the given exception to an array.
	 *
	 * @param  \Throwable  $e
	 * @return array
	 */
	protected function convertExceptionToArray(Throwable $e)
	{
		return config('app.debug') ? [
			'message' => $e->getMessage(),
			'errors' => null,
			'exception' => get_class($e),
			'file' => $e->getFile(),
			'line' => $e->getLine(),
			'trace' => collect($e->getTrace())->map(function ($trace) {
				return Arr::except($trace, ['args']);
			})->all(),
		] : [
			'message' => $this->isHttpException($e) ? $e->getMessage() : 'Server Error',
			'errors' => null
		];
	}
}
