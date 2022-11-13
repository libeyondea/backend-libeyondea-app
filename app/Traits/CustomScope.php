<?php

namespace App\Traits;

trait CustomScope
{
	public function scopePagination($queryBuilder, $page = 1, $pageSize = 10, $sortDirection = 'desc', $sortBy = 'created_at')
	{
		$page = request()->get('page', $page);
		$pageSize = request()->get('page_size', $pageSize);
		$sortDirection = request()->get('sort_direction', $sortDirection);
		$sortBy = request()->get('sort_by', $sortBy);

		$queryBuilder = $queryBuilder->orderBy($sortBy, $sortDirection)->paginate($pageSize, ['*'], 'page', $page);

		return $queryBuilder;
	}
}
