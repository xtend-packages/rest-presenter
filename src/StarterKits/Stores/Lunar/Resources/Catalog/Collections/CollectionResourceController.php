<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Collections;

use Illuminate\Http\Request;
use Lunar\Models\Collection;
use Spatie\LaravelData\Data;
use XtendPackages\RESTPresenter\Resources\ResourceController;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Collections\Filters\CollectionGroup;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Collections\Presenters\Category\Category;

class CollectionResourceController extends ResourceController
{
    protected static string $model = Collection::class;

    public function index(Request $request): \Illuminate\Support\Collection
    {
        $collections = $this->getModelQueryInstance()->get();

        return $collections->map(
            fn ($collection): \Spatie\LaravelData\Data => $this->present($request, $collection),
        );
    }

    public function show(Request $request, Collection $collection): Data
    {
        return $this->present($request, $collection);
    }

    public function filters(): array
    {
        return [
            'collection_group' => CollectionGroup::class,
        ];
    }

    public function presenters(): array
    {
        return [
            'category' => Category::class,
        ];
    }
}
