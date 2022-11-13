<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Traits\ApiResponser;
use App\Transformers\SettingTransformer;
use App\Utils\Logger;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
	use ApiResponser;

	public function show()
	{
		try {
			$setting = Setting::where('user_id', auth()->user()->id)->firstOrFail();

			return $this->respondSuccess(fractal($setting, new SettingTransformer())->toArray());
		} catch (Exception $e) {
			Logger::emergency($e);
			return $this->respondError($e->getMessage());
		}
	}

	public function update(Request $request)
	{
		try {
			$attrs = $request->all();

			DB::beginTransaction();
			$setting = Setting::where('user_id', auth()->user()->id)->firstOrFail();

			if ($setting->isInvalidFor('UPDATE')) {
				return $this->respondBadRequest(
					'The given data was invalid.',
					$setting
						->validator()
						->errors()
						->messages()
				);
			}

			$setting->theme = $attrs['theme'];
			$setting->save();
			DB::commit();

			return $this->respondSuccess(fractal($setting, new SettingTransformer())->toArray());
		} catch (Exception $e) {
			DB::rollBack();
			Logger::emergency($e);
			return $this->respondError($e->getMessage());
		}
	}
}
