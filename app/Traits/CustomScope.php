<?php

namespace App\Traits;

trait CustomScope
{
	public function scopeSearchCriteriaInQueryBuilder($queryBuilder, array $fields = [])
	{
		$searchCriteria = request()->all();

		foreach ($searchCriteria as $key => $value) {
			if (in_array($key, ['page', 'per_page', 'order_by', 'sort_by'])) {
				continue;
			}

			if (in_array($key, ['keyword'])) {
				$queryBuilder->where(function ($q) use ($fields, $value) {
					foreach ($fields as $field) {
						$q->orWhere($field, 'like', '%' . $value . '%');
					}
				});
			} else {
				$allValues = explode(',', $value);
				if (count($allValues) > 1) {
					$queryBuilder->whereIn($key, $allValues);
				} else {
					$queryBuilder->where($key, $value);
				}
			}
		}
		return $queryBuilder;
	}

	public function scopePagination($queryBuilder)
	{
		$page = request()->get('page', 1);
		$perPage = request()->get('per_page', 10);
		$orderBy = request()->get('order_by', 'created_at');
		$sortBy = request()->get('sort_by', 'desc');

		$queryBuilder = $queryBuilder->orderBy($orderBy, $sortBy)->paginate($perPage, ['*'], 'page', $page);

		return $queryBuilder;
	}
}
