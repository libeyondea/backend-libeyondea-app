<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\QueryBuilder;

trait CustomScope
{
	// public function scopeFilter(Builder $queryBuilder)
	// {
	// 	$attrs = request()->all();

	// 	foreach ($attrs as $key => $value) {
	// 		if (in_array($key, ['page', 'per_page'])) {
	// 			continue;
	// 		}

	// 		if (in_array($key, ['keyword'])) {
	// 			$queryBuilder->where(function ($q) use ($value) {
	// 				foreach ($this->filterable ?? [] as $field) {
	// 					$q->orWhere($field, 'like', '%' . $value . '%');
	// 				}
	// 			});
	// 		} elseif (in_array($key, $this->filterable ?? [])) {
	// 			$allValues = explode(',', $value);
	// 			if (count($allValues) > 1) {
	// 				$queryBuilder->whereIn($key, $allValues);
	// 			} else {
	// 				$queryBuilder->where($key, $value);
	// 			}
	// 		} elseif (in_array($key, ['order_by'])) {
	// 			$orderBy = in_array($value, $this->sortable ?? []) ? $value : 'created_at';
	// 			$sortBy = in_array(request()->get('sort_by'), ['desc', 'asc']) ? request()->get('sort_by') : 'desc';

	// 			$queryBuilder->orderBy($orderBy, $sortBy);
	// 		}
	// 	}

	// 	return $queryBuilder;
	// }

	public function scopeSearch(Builder $queryBuilder)
	{
		if (request()->has('keyword')) {
			$queryBuilder->where(function ($q) {
				foreach ($this->filterable ?? [] as $field) {
					$q->orWhere($field, 'like', '%' . request()->keyword . '%');
				}
			});
		}

		return $queryBuilder;
	}

	public function scopeFilter(Builder $queryBuilder)
	{
		$queryBuilder = QueryBuilder::for($queryBuilder)
			->allowedFilters($this->filterable ?? [])
			->defaultSort('created_at')
			->allowedSorts($this->sortable ?? []);

		return $queryBuilder;
	}

	public function scopePagination(Builder $queryBuilder)
	{
		$page = request()->get('page', 1);
		$perPage = request()->get('per_page', 10);

		$queryBuilder = $queryBuilder->paginate($perPage, ['*'], 'page', $page);

		return $queryBuilder;
	}
}
