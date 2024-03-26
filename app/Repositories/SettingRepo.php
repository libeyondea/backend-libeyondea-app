<?php

namespace App\Repositories;

use App\Models\Setting;
use App\Transformers\SettingTransformer;
use App\Utils\Logger;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SettingRepo extends AbstractBaseRepo
{
	public function show(): array
	{
		try {
			$setting = Setting::where('user_id', auth()->user()->id)->first();

			if ($setting) {
				$results = fractal($setting, new SettingTransformer())->toArray();

				return [
					'success' => true,
					'code' => Response::HTTP_OK,
					'message' => 'Get setting success.',
					'data' => $results,
				];
			} else {
				return [
					'success' => false,
					'code' => Response::HTTP_NOT_FOUND,
					'message' => 'Setting not found.',
				];
			}
		} catch (Exception $e) {
			Logger::emergency($e);
			return [
				'success' => false,
				'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
				'message' => $e->getMessage(),
			];
		}
	}

	public function update(Request $request): array
	{
		try {
			$validator = Validator::make($request->all(), [
				'language' => ['required', Rule::in(['en', 'vi'])],
			]);

			if ($validator->fails()) {
				return [
					'success' => false,
					'code' => Response::HTTP_BAD_REQUEST,
					'message' => $validator->errors()->first(),
				];
			}

			$validatedData = $validator->validated();

			DB::beginTransaction();
			$setting = Setting::where('user_id', auth()->user()->id)->first();

			if ($setting) {
				$setting->language = $validatedData['language'];
				$setting->save();

				return [
					'success' => true,
					'code' => Response::HTTP_OK,
					'message' => 'Update setting success.',
				];
			} else {
				return [
					'success' => false,
					'code' => Response::HTTP_NOT_FOUND,
					'message' => 'Setting not found.',
				];
			}
			DB::commit();
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
