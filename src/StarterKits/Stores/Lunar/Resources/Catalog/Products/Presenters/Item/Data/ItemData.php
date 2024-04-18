<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Item\Data;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Data\Response\MediaData;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Data\Response\PriceData;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Data\Response\UrlData;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Detail\Data\Variant\ColorData;

class ItemData extends Data
{
    public function __construct(
        public int $id,
        public UrlData $slug,
        public string $name,
        public ?string $availability,
        /** @var Collection<ColorData> */
        public Collection $colors,
        public PriceData $price,
        public ?MediaData $image,
    ) {
    }
}
