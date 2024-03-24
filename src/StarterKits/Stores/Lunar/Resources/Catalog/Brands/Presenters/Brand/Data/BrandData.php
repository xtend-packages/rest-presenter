<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Brands\Presenters\Brand\Data;

use Spatie\LaravelData\Data;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Data\Response\MediaData;

class BrandData extends Data
{
    public function __construct(
        public int $id,
        public string $slug,
        public string $name,
        public ?MediaData $image,
    ) {
    }
}
