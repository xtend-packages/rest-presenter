<?php

namespace XtendPackages\RESTPresenter\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;

trait WithResourceFiltering
{
    /**
     * @var Collection<string, string>
     */
    public Collection $filters;

    /**
     * @template TModelClass of \Illuminate\Database\Eloquent\Model
     * @param  Builder<TModelClass>  $query
     */
    protected function applyFilters(Builder $query): mixed
    {
        return app(Pipeline::class)
            ->send($query)
            ->through($this->getFilters())
            ->thenReturn();
    }

    /**
     * @return array<string, string>
     */
    protected function getFilters(): array
    {
        return method_exists($this, 'filters') ? $this->filters() : [];
    }
}
