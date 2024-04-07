<?php

namespace XtendPackages\RESTPresenter\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;

trait WithResourceActions
{
    public Collection $actions;

    protected function applyActions($query): Builder
    {
        return app(Pipeline::class)
            ->send($query)
            ->through($this->getActions())
            ->thenReturn();
    }

    protected function getActions(): array
    {
        return method_exists($this, 'actions') ? $this->actions() : [];
    }
}
