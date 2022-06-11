<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSettingRequest;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use App\Traits\ApiResponser;

class SettingController extends Controller
{
	use ApiResponser;

	public function show()
	{
		$setting = Setting::findOrFail(auth()->user()->id);
		return $this->respondSuccess(new SettingResource($setting));
	}

	public function update(UpdateSettingRequest $request)
	{
		$settingData = $request->all();
		$setting = Setting::where('user_id', auth()->user()->id)->firstOrFail();
		$setting->update($settingData);
		return $this->respondSuccess(new SettingResource($setting));
	}
}
