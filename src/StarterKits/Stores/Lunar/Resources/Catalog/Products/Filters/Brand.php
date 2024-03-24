<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Filters;

use Illuminate\Database\Eloquent\Builder;
use XtendPackages\RESTPresenter\Concerns\InteractsWithRequest;

class Brand
{
    use InteractsWithRequest;

    public function handle(Builder $query, callable $next): Builder
    {
        if ($this->hasFilter('brand_id')) {
            $query->where('brand_id', $this->filterBy('brand_id'));
        }

        return $next($query);
    }
}
