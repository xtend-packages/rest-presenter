<?php

namespace XtendPackages\RESTPresenter\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait InteractsWithModel
{
    protected static string $model = Model::class;

    protected static Builder $modelQuery;

    protected function setModel(string $model): void
    {
        static::$model = $model;
    }

    protected function getModel(): Model
    {
        return new static::$model;
    }

    protected function getModelQuery(): Builder
    {
        return $this->getModel()->query();
    }

    protected function setModelQuery(Builder $query): void
    {
        static::$modelQuery = $query;
    }

    protected function getModelQueryInstance(): Builder
    {
        return static::$modelQuery;
    }
}
