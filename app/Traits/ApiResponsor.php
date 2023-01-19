<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponsor
{
	protected function responseSuccess(string $message, $data, int $code = Response::HTTP_OK)
	{
		return response()->json(
			[
				'success' => true,
				'code' => $code,
				'message' => $message,
				'data' => $data,
			],
			$code
		);
	}

	protected function responseSuccessPaginate(string $message, array $data, array $columns, array $meta, int $code = Response::HTTP_OK)
	{
		return response()->json(
			[
				'success' => true,
				'code' => $code,
				'message' => $message,
				'data' => $data,
				'columns' => $columns,
				'meta' => $meta,
			],
			$code
		);
	}

	protected function responseError(string $message, int $code = Response::HTTP_INTERNAL_SERVER_ERROR)
	{
		return response()->json(
			[
				'success' => false,
				'code' => $code,
				'message' => $message,
			],
			$code
		);
	}

	protected function responseBadRequest(string $message = 'Bad Request.')
	{
		return $this->responseError($message, Response::HTTP_BAD_REQUEST);
	}

	protected function responseUnauthorized(string $message = 'Unauthorized.')
	{
		return $this->responseError($message, Response::HTTP_UNAUTHORIZED);
	}

	protected function responseForbidden(string $message = 'Forbidden.')
	{
		return $this->responseError($message, Response::HTTP_FORBIDDEN);
	}

	protected function responseNotFound(string $message = 'Not Found.')
	{
		return $this->responseError($message, Response::HTTP_NOT_FOUND);
	}

	protected function responseUnprocessableEntity(string $message = 'Unprocessable Entity.')
	{
		return $this->responseError($message, Response::HTTP_UNPROCESSABLE_ENTITY);
	}

	protected function responseInternalError(string $message = 'Internal Error.')
	{
		return $this->responseError($message, Response::HTTP_INTERNAL_SERVER_ERROR);
	}
}
