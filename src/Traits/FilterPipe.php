<?php

namespace FilterPipe\Traits;

use Illuminate\Support\Arr;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Database\Eloquent\Builder;

trait FilterPipe
{
    /**
     * @param Builder $builder
     * @param array|string $filter
     *
     * @return Builder
     */
    public function scopeFilter(Builder $builder, $filter)
    {
        return app(Pipeline::class)
            ->send($builder)
            ->through(Arr::wrap($filter))
            ->thenReturn();
    }
}
