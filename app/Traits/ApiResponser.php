<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponser
{
	protected function respond($data, $statusCode = Response::HTTP_OK, $headers = [])
	{
		return response()->json($data, $statusCode, $headers);
	}

	protected function respondSuccess($data = null, $statusCode = Response::HTTP_OK)
	{
		return $this->respond(
			[
				'data' => $data,
			],
			$statusCode
		);
	}

	protected function respondSuccessWithList($data = [], $columns = [], $total = 0, $statusCode = Response::HTTP_OK)
	{
		return $this->respond(
			[
				'data' => $data,
				'columns' => $columns,
				'pagination' => [
					'total' => $total,
				],
			],
			$statusCode
		);
	}

	protected function respondError($message, $errors = null, $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR)
	{
		return $this->respond(
			$errors
				? [
					'message' => $message,
					'errors' => $errors,
				]
				: [
					'message' => $message,
				],
			$statusCode
		);
	}

	protected function respondBadRequest($message = 'Bad Request', $errors = null)
	{
		return $this->respondError($message, $errors, Response::HTTP_BAD_REQUEST);
	}

	protected function respondUnauthorized($message = 'Unauthorized', $errors = null)
	{
		return $this->respondError($message, $errors, Response::HTTP_UNAUTHORIZED);
	}

	protected function respondForbidden($message = 'Forbidden', $errors = null)
	{
		return $this->respondError($message, $errors, Response::HTTP_FORBIDDEN);
	}

	protected function respondNotFound($message = 'Not Found', $errors = null)
	{
		return $this->respondError($message, $errors, Response::HTTP_NOT_FOUND);
	}

	protected function respondUnprocessableEntity($message = 'Unprocessable Entity', $errors = null)
	{
		return $this->respondError($message, $errors, Response::HTTP_UNPROCESSABLE_ENTITY);
	}

	protected function respondInternalError($message = 'Internal Error', $errors = null)
	{
		return $this->respondError($message, $errors, Response::HTTP_INTERNAL_SERVER_ERROR);
	}
}
