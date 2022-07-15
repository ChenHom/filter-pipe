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
        return $next($this->apply($builder, $column, $this->detectorSearchColumn($column), ...$operator));
    }

    protected function search(Builder $builder, string $column, string $value, string $operator): Builder
    {
        if ($method = $this->whereMultipleValue($operator)) {
            return $builder->{$method}($column, explode(',', $value));
        }
        return $builder->where($column, $operator ?: '=', $value);
    }

    /**
     *
     * @param Builder $builder
     * @param string $fields
     * @param string $value
     * @param string $operators
     * @return Builder
     */
    protected function apply(Builder $builder, string $fields, string $column, ...$operators): Builder
    {
        return $builder->when(
            request($fields),
            function (Builder $builder, $query) use ($column, $operators) {
                foreach (Arr::wrap($query) as $key => $possible) {
                    $builder = $builder->when(
                        $possible,
                        fn ($search, $value) => $this->search($search, $column, $value, $operators[$key] ?? '')
                    );
                }
                return $builder;
            }
        );
    }

    protected function whereMultipleValue($operator): string
    {
        $method = [
            'between' => 'whereBetween',
            'in' => 'whereIn',
        ];

        return data_get($method, $operator, '');
    }

    protected function detectorSearchColumn(string $string): string
    {
        return Arr::last(explode(static::$columnAliasDelimiter, $string));
    }
}
