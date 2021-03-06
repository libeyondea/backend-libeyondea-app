<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MeResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
	 */
	public function toArray($request)
	{
		return [
			'id' => $this->id,
			'first_name' => $this->first_name,
			'last_name' => $this->last_name,
			'user_name' => $this->user_name,
			'avatar_url' => $this->avatar_url,
			'email' => $this->email,
			'role' => $this->role,
			'actived' => $this->actived,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
			'setting' => [
				'id' => $this->setting->id,
				'fixed_navbar' => $this->setting->fixed_navbar,
				'fixed_footer' => $this->setting->fixed_footer,
				'created_at' => $this->setting->created_at,
				'updated_at' => $this->setting->updated_at,
			],
		];
	}
}
