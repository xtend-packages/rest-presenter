<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\CollectionGroups\Presenters\CategoryTree;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Lunar\Models\Collection;
use Lunar\Models\CollectionGroup;
use XtendPackages\RESTPresenter\Concerns\InteractsWithPresenter;
use XtendPackages\RESTPresenter\Contracts\Presentable;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\CollectionGroups\Presenters\CategoryTree\Data\TreeData;

class CategoryTree implements Presentable
{
    use InteractsWithPresenter;

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

    protected function generateTree(\Illuminate\Support\Collection $collections): array
    {
        return $collections->map(function (Collection $collection) {
            return [
                'id' => $collection->id,
                'name' => $collection->translateAttribute('name'),
                'slug' => $this->generateSlugComputed($collection),
                'children' => $this->generateTree($collection->children),
            ];
        })->toArray();
    }

    protected function generateSlugComputed(Collection $collection): string
    {
        return $collection->id . '-' . Str::slug($collection->translateAttribute('name'));
    }
}
