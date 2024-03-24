<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\CollectionGroups\Presenters\CategoryTree;

use Illuminate\Http\Request;
use Lunar\Models\CollectionGroup;
use XtendPackages\RESTPresenter\Concerns\InteractsWithPresenter;
use XtendPackages\RESTPresenter\Contracts\Presentable;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\CollectionGroups\Presenters\CategoryTree\Concerns\WithGenerateCollectionsTree;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\CollectionGroups\Presenters\CategoryTree\Data\TreeData;

class CategoryTree implements Presentable
{
    use InteractsWithPresenter;
    use WithGenerateCollectionsTree;

    public function __construct(
        private Request $request,
        private ?CollectionGroup $model,
    ) {}

    public function transform(): TreeData
    {
        $collections = $this->getCollections();

        return TreeData::fromModel(
            model: $this->model,
            categories: $this->generateTree($collections),
        );
    }

    protected function getCollections(): \Illuminate\Support\Collection
    {
        return $this->model
            ->collections()
            ->where('parent_id', null)
            ->get();
    }
}
