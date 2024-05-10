<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait InteractsWithModel
{
    protected static string $model = Model::class;

    /**
     * @var Builder<Model>
     */
    protected static Builder $modelQuery;

    protected function setModel(string $modelClass): void
    {
        static::$model = $modelClass;
    }

    protected function getModel(): Model
    {
        $modelClass = static::$model;

        return type(new $modelClass)->as(Model::class);
    }

    /**
     * @return Builder<Model>
     */
    protected function getModelQuery(): Builder
    {
        return $this->getModel()->query();
    }

    /**
     * @param  Builder<Model>  $query
     */
    protected function setModelQuery(Builder $query): void
    {
        static::$modelQuery = $query->withoutGlobalScopes();
    }

    /**
     * @return Builder<Model>
     */
    protected function getModelQueryInstance(): Builder
    {
        return static::$modelQuery;
    }
}
