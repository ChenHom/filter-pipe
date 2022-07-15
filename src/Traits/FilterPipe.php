<?php

namespace FilterPipe\Traits;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Arr;

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
