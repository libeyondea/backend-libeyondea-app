<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponser;
use App\Utils\Logger;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
	use ApiResponser;

	public function upload(Request $request): JsonResponse
	{
		try {
			$validator = Validator::make($request->all(), [
				'image' => 'required|image|mimes:jpeg,jpg,png,gif|max:2048',
			]);

			if ($validator->fails()) {
				return $this->respondBadRequest(
					'The given data was invalid.',
					$validator
						->validator()
						->errors()
						->messages()
				);
			}

			if ($request->hasfile('image')) {
				$file = $request->file('image');
				$imageName = time() . '.' . $file->extension();
				$imageContent = $file->getContent();
				Storage::disk('image')->put($imageName, $imageContent);

				return $this->respondSuccess([
					'name' => $imageName,
					'url' => config('app.image_url') . '/' . $imageName,
				]);
			}

			return $this->respondInternalError();
		} catch (Exception $e) {
			Logger::emergency($e);
			return $this->respondError($e->getMessage());
		}
	}
}
