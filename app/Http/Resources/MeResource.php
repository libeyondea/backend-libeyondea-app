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
			'avatar' => $this->avatar,
			'email' => $this->email,
			'role' => $this->role,
			'status' => $this->status,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
			'setting' => [
				'id' => $this->setting->id,
				'theme' => $this->setting->theme,
				'created_at' => $this->setting->created_at,
				'updated_at' => $this->setting->updated_at,
			],
		];
	}
}
