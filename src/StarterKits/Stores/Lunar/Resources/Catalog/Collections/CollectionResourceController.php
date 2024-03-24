<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Collections;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lunar\Models\Collection;
use XtendPackages\RESTPresenter\Resources\ResourceController;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Collections\Filters\CollectionGroup;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Collections\Presenters\Category\Category;

class CollectionResourceController extends ResourceController
{
    protected static string $model = Collection::class;

    public function index(Request $request): JsonResponse
    {
        return response()->json(['collections' => $this->getModelQueryInstance()->get()]);
    }

    public function show(Request $request, Collection $collection): JsonResponse
    {
        return response()->json(
            data: $this->present($request, $collection),
        );
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
