<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Collections\Filters;

use Illuminate\Database\Eloquent\Builder;
use XtendPackages\RESTPresenter\Concerns\InteractsWithRequest;

class CollectionGroup
{
    use InteractsWithRequest;

    public function handle(Builder $query, callable $next): Builder
    {
        if ($this->hasFilter('collection_group_id')) {
            $query->where('collection_group_id', $this->filterBy('collection_group_id'));
        }

        return $next($query);
    }
}
