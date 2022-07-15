<?php

namespace FilterPipe;

use FilterPipe\Filters\ExactFilter;
use FilterPipe\Filters\RangeFilter;
use FilterPipe\Filters\RelativeFilter;
use Illuminate\Support\ServiceProvider;

class FilterPipeServiceProvider extends ServiceProvider
{
    /**
     * @var array<string, string>
     */
    protected $filters = [
        'relative' => RelativeFilter::class,
        'exact' => ExactFilter::class,
        // 'boolean' => '',
        'range' => RangeFilter::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->filters as $name => $filter) {
            $this->app->singleton($name, fn () => new $filter);
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
