<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponsor;
use App\Utils\Logger;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImageRepo extends AbstractBaseRepo
{
	use ApiResponsor;

	public function upload(Request $request): array
	{
		try {
			$validator = Validator::make($request->all(), [
				'image' => 'required|image|mimes:jpeg,jpg,png,gif|max:2048',
			]);

			if ($validator->fails()) {
				return [
					'success' => false,
					'code' => Response::HTTP_BAD_REQUEST,
					'message' => $validator->errors()->first(),
				];
			}

			if ($request->hasfile('image')) {
				$image = $request->file('image');
				$imageName = time() . '.' . $image->getClientOriginalExtension();
				$imageContent = $image->getContent();

				Storage::disk('image')->put($imageName, $imageContent);

				return [
					'success' => true,
					'code' => Response::HTTP_OK,
					'message' => 'Upload image success.',
					'data' => [
						'name' => $imageName,
						'url' => config('app.image_url') . '/' . $imageName,
					],
				];
			}

			return [
				'success' => false,
				'code' => Response::HTTP_BAD_REQUEST,
				'message' => 'No image provided.',
			];
		} catch (Exception $e) {
			DB::rollBack();
			Logger::emergency($e);
			return [
				'success' => false,
				'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
				'message' => $e->getMessage(),
			];
		}
	}
}
