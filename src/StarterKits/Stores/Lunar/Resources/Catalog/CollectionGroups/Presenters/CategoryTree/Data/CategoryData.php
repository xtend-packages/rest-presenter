<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\CollectionGroups\Presenters\CategoryTree\Data;

use Spatie\LaravelData\Data;

final class CategoryData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public ?array $children,
    ) {
        // @todo: Support nested children in the future not currently required
    }
}
