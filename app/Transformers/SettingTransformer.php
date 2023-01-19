<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Setting;

class SettingTransformer extends TransformerAbstract
{
	/**
	 * List of resources to automatically include
	 *
	 * @var array
	 */
	protected array $defaultIncludes = [
		//
	];

	/**
	 * List of resources possible to include
	 *
	 * @var array
	 */
	protected array $availableIncludes = [
		//
	];

	/**
	 * A Fractal transformer.
	 *
	 * @return array
	 */
	public function transform(Setting $setting): array
	{
		return [
			'id' => $setting->id,
			'language' => $setting->language,
			'created_at' => $setting->created_at,
			'updated_at' => $setting->updated_at,
		];
	}
}
