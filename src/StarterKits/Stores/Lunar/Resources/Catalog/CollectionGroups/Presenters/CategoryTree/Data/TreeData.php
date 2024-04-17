<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\CollectionGroups\Presenters\CategoryTree\Data;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

final class TreeData extends Data
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
            id: $model->getKey(),
            name: $model->name ?? '',
            categories: collect($categories)->map(
                fn (array $category): \XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\CollectionGroups\Presenters\CategoryTree\Data\CategoryData => CategoryData::from($category)
            ),
        );
    }
}
