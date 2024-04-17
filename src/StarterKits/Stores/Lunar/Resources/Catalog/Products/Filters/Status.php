<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Filters;

use Illuminate\Database\Eloquent\Builder;
use XtendPackages\RESTPresenter\Concerns\InteractsWithRequest;

final class Status
{
    use InteractsWithRequest;

    public function handle(Builder $query, callable $next): Builder
    {
        if ($this->hasFilter('status')) {
            $query->where('status', $this->filterBy('status'));
        }

        return $next($query);
    }
}
