<?php

namespace FilterPipe\Filters;

use FilterPipe\Contracts\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

class RelativeFilter extends QueryFilter
{
    protected function search(Builder $builder, string $column, string $value, string $operator): Builder
    {
        $value = match ($operator) {
            'both' => "%{$value}%",
            'left' => "%{$value}",
            'right' => "{$value}%",
            default => "{$value}%",
        };

        return $builder->where($column, 'like', $value);
    }
}
