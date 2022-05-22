<?php

namespace App\Traits;

trait CustomScope
{
	public function scopePagination($query, $page = 1, $pageSize = 10,  $sortDirection = 'desc', $sortBy = 'created_at')
	{
		$page = request()->get('page', $page);
		$pageSize = request()->get('page_size', $pageSize);
		$sortDirection = request()->get('sort_direction', $sortDirection);
		$sortBy = request()->get('sort_by', $sortBy);

		return $query->orderBy($sortBy, $sortDirection)->skip(($page - 1) * $pageSize)->limit($pageSize)->get();
	}
}
