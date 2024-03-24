<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\CollectionGroups;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Lunar\Models\CollectionGroup;
use Spatie\LaravelData\Data;
use XtendPackages\RESTPresenter\Resources\ResourceController;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\CollectionGroups\Presenters\CategoryTree\CategoryTree;

class CollectionGroupResourceController extends ResourceController
{
    protected static string $model = CollectionGroup::class;

    public function index(Request $request): Collection
    {
        $collectionGroups = $this->getModelQueryInstance()->get();

        return $collectionGroups->map(
            fn (CollectionGroup $collectionGroup) => $this->present($request, $collectionGroup),
        );
    }

    public function show(Request $request, CollectionGroup $collectionGroup): Data
    {
        return $this->present($request, $collectionGroup);
    }

    public function presenters(): array
    {
        return [
            'category-tree' => CategoryTree::class,
        ];
    }
}
