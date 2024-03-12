<?php

namespace XtendPackages\RESTPresenter\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;

trait WithResourceFiltering
{
    public Collection $filters;

    protected function applyFilters($query): Builder
    {
        return app(Pipeline::class)
            ->send($query)
            ->through($this->getFilters())
            ->thenReturn();
    }

    protected function getFilters(): array
    {
        return method_exists($this, 'filters') ? $this->filters() : [];
    }
}
