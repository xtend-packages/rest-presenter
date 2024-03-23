<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Collections\Presenters\Category\Data;

use App\Api\v1\Data\Response\MediaData;
use Spatie\LaravelData\Data;

class CategoryData extends Data
{
    public function __construct(
        public int $id,
        public string $slug,
        public string $name,
        public ?string $description,
        public ?MediaData $banner,
    ) {
    }
}
