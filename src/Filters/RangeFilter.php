<?php

namespace FilterPipe\Filters;

use FilterPipe\Contracts\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class RangeFilter extends QueryFilter
{
    protected function search(Builder $builder, string $column, $value, $operator = null): Builder
    {
        $value = explode(',', $value);

        if ($this->isWhereBetweenCondition($operator)) {
            return $builder->whereBetween($column, $value);
        }

        foreach ($value as $key => $v) {
            if ($v) {
                $builder = parent::search($builder,  $operator[$key], $v);
            }
        }

        return $builder;
    }

    protected function isWhereBetweenCondition(string $operator)
    {
        return $operator === 'in';
    }
}
