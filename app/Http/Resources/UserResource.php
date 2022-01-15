<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
			'email_verified_at' => $this->email_verified_at,
			'role' => $this->role,
			'status' => $this->status,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
		];
	}
}
