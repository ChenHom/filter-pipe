<?php

namespace FilterPipe\Filters;

use FilterPipe\Contracts\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

class ExactFilter extends QueryFilter
{
    protected function search(Builder $builder, string $column, $value, $operator = null): Builder
    {
        if ($this->isWhereInCondition($operator)) {
            return $builder->whereIn($column, explode(',', $value));
        }
        return parent::search(builder: $builder,column: $column,value: $value);
    }

    protected function isWhereInCondition(string $operator)
    {
        return $operator === 'in';
    }
}
