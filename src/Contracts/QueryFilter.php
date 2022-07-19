<?php

namespace FilterPipe\Contracts;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

abstract class QueryFilter
{
    protected static $columnAliasDelimiter = '@';

    public function handle(Builder $builder, Closure $next, string $column, ...$operator): Builder
    {
        return $next(
            $this->apply(
                $builder,
                $column,
                $this->detectorSearchColumn($column),
                ...$operator
            )
        );
    }

    /**
     *
     * @param Builder $builder
     * @param string $column
     * @param string|array $value
     * @param string|array $operator
     * @return Builder
     */
    protected function search(Builder $builder, string $column, $value, $operator = null): Builder
    {
        return $builder->where($column, $operator , $value);
    }

    /**
     *
     * @param Builder $builder
     * @param string $fields
     * @param string $value
     * @param array $operators
     * @return Builder
     */
    protected function apply(Builder $builder, string $fields, string $column, ...$operators): Builder
    {
        return $builder->when(
            request($fields),
            fn (Builder $builder, $query) => $this->search($builder, $column, $query, $operators)
        );
    }

    protected function detectorSearchColumn(string $string): string
    {
        return Arr::last(explode(static::$columnAliasDelimiter, $string));
    }
}
