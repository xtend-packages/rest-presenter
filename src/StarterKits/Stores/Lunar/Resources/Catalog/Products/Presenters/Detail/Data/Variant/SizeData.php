<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Detail\Data\Variant;

use Lunar\Models\ProductOptionValue;
use Spatie\LaravelData\Data;

final class SizeData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public int $position,
    ) {
    }

    public static function fromModel(ProductOptionValue $value): self
    {
        return new self(
            id: $value->id,
            name: $value->translate('name'),
            position: $value->position,
        );
    }
}
