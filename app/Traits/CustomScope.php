<?php

namespace App\Traits;

trait CustomScope
{
	public function scopePagination($query, $page = 1, $limit = 10,  $sortDirection = 'desc', $sortBy = 'created_at')
	{
		$page = request()->get('page', $page);
		$limit = request()->get('limit', $limit);
		$sortDirection = request()->get('sort_direction', $sortDirection);
		$sortBy = request()->get('sort_by', $sortBy);

		return $query->orderBy($sortBy, $sortDirection)->skip(($page - 1) * $limit)->limit($limit)->get();
	}
}
