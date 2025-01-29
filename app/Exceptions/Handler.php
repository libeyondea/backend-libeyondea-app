<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
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
			? response()->json(
				[
					'success' => false,
					'code' => 401,
					'message' => $exception->getMessage(),
				],
				401
			)
			: redirect()->guest($exception->redirectTo($request) ?? route('login'));
	}

	/**
	 * Convert the given exception to an array.
	 *
	 * @param  \Throwable  $e
	 * @return array
	 */
	protected function convertExceptionToArray(Throwable $e)
	{
		return config('app.debug')
			? [
				'success' => false,
				'code' => $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500,
				'message' => $e->getMessage(),
				'debug' => [
					'exception' => get_class($e),
					'file' => $e->getFile(),
					'line' => $e->getLine(),
					'trace' => collect($e->getTrace())->map(fn($trace) => Arr::except($trace, ['args']))->all(),
				],
			]
			: [
				'success' => false,
				'code' => $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500,
				'message' => $this->isHttpException($e) ? $e->getMessage() : 'Server Error',
			];
	}
}
