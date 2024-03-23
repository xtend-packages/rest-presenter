<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Detail\Data;

use Lunar\Models\Collection;
use Spatie\LaravelData\Data;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Data\Response\UrlData;

class StyleData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public UrlData $url,
    ) {
    }

    public static function fromModel(Collection $style, UrlData $url): self
    {
        return new self(
            id: $style->id,
            name: $style->translateAttribute('name'),
            url: $url,
        );
    }
}
