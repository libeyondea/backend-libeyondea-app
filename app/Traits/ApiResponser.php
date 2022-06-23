<?php

namespace App\Traits;

trait ApiResponser
{

	protected function respond($data, $statusCode = 200, $headers = [])
	{
		return response()->json($data, $statusCode, $headers);
	}

	protected function respondSuccess($data = null, $statusCode = 200, $headers = [])
	{
		return $this->respond(
			[
				'data' => $data,
			],
			$statusCode,
			$headers
		);
	}

	protected function respondSuccessWithPagination($data = null, $total = 0, $statusCode = 200, $headers = [])
	{
		return $this->respond(
			[
				'data' => $data,
				'pagination' => [
					'total' => $total
				]

			],
			$statusCode,
			$headers
		);
	}

	protected function respondError($message, $errors = null, $statusCode = 500)
	{
		return $this->respond(
			$errors ? [
				'message' => $message,
				'errors' => $errors

			] : [
				'message' => $message,

			],
			$statusCode
		);
	}

	protected function respondBadRequest($message = 'Bad Request', $errors = null)
	{
		return $this->respondError($message, $errors, 400);
	}

	protected function respondUnauthorized($message = 'Unauthorized', $errors = null)
	{
		return $this->respondError($message, $errors, 401);
	}

	protected function respondForbidden($message = 'Forbidden', $errors = null)
	{
		return $this->respondError($message, $errors, 403);
	}

	protected function respondNotFound($message = 'Not Found', $errors = null)
	{
		return $this->respondError($message, $errors, 404);
	}

	protected function respondUnprocessableEntity($message = 'Unprocessable Entity', $errors = null)
	{
		return $this->respondError($message, $errors, 422);
	}

	protected function respondInternalError($message = 'Internal Error', $errors = null)
	{
		return $this->respondError($message, $errors, 500);
	}
}
