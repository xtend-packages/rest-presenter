<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\CollectionGroups;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lunar\Models\CollectionGroup;
use XtendPackages\RESTPresenter\Resources\ResourceController;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\CollectionGroups\Presenters\CategoryTree\CategoryTree;

class CollectionGroupResourceController extends ResourceController
{
    protected static string $model = CollectionGroup::class;

    public function index(Request $request): JsonResponse
    {
        return response()->json(['collectionGroups' => $this->getModelQueryInstance()->get()]);
    }

    public function show(Request $request, CollectionGroup $collectionGroup): JsonResponse
    {
        return response()->json(
            data: $this->present($request, $collectionGroup),
        );
    }

    public function presenters(): array
    {
        return [
            'CategoryTree' => CategoryTree::class,
        ];
    }
}
