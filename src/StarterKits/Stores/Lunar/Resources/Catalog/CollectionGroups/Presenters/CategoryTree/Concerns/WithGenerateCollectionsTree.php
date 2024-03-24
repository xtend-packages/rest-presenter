<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\CollectionGroups\Presenters\CategoryTree\Concerns;

use Illuminate\Support\Str;
use Lunar\Models\Collection;

trait WithGenerateCollectionsTree
{
    public function generateTree(\Illuminate\Support\Collection $collections): array
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
