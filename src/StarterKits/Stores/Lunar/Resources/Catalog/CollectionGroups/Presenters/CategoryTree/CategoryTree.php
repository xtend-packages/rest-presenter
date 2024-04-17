<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\CollectionGroups\Presenters\CategoryTree;

use Illuminate\Http\Request;
use Lunar\Models\CollectionGroup;
use Spatie\LaravelData\Data;
use XtendPackages\RESTPresenter\Concerns\InteractsWithPresenter;
use XtendPackages\RESTPresenter\Contracts\Presentable;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\CollectionGroups\Presenters\CategoryTree\Concerns\WithGenerateCollectionsTree;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\CollectionGroups\Presenters\CategoryTree\Data\TreeData;

final class CategoryTree implements Presentable
{
    use InteractsWithPresenter;
    use WithGenerateCollectionsTree;

    public function __construct(
        protected Request $request,
        protected ?CollectionGroup $model,
    ) {
    }

    public function transform(): TreeData|Data
    {
        $collections = $this->getCollections();

        return TreeData::fromModel(
            model: $this->model,
            categories: $this->generateTree($collections),
        );
    }

    private function getCollections(): \Illuminate\Support\Collection
    {
        return $this->model
            ->collections()
            ->where('parent_id', null)
            ->get();
    }
}
