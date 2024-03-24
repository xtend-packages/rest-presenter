<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\CollectionGroups\Presenters\CategoryTree\Data;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class TreeData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        /** @var Collection<int, CategoryData> */
        public Collection $categories,
    ) {
    }

    public static function fromModel(Model $model, array $categories): self
    {
        return new self(
            id: $model->id,
            name: $model->name,
            categories: collect($categories)->map(
                fn (array $category) => CategoryData::from($category)
            ),
        );
    }

}
