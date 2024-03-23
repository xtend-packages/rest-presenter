<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Detail\Data;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Data\Response\UrlData;

class DetailData extends Data
{
    public function __construct(
        public int $id,
        public string $sku,
        public UrlData $url,
        public StyleData $style,
        public string $name,
        public string $description,
        public ?string $availability,
        public int $stock,
        /** @var Collection<int, VariantData> */
        public Collection $variants,
        /** @var Collection<int, \XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Detail\Data\Variant\ColorData> */
        public Collection $colors,
        /** @var Collection<int, \XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Detail\Data\Variant\SizeData> */
        public Collection $sizes,
        /** @var Collection<int, \XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Data\Response\PriceData> */
        public Collection $prices,
        /** @var Collection<int, \XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Data\Response\MediaData> */
        public Collection $images,
    ) {
    }
}
