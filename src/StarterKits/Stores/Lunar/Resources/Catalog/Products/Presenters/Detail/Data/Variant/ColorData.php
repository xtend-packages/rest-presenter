<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Detail\Data\Variant;

use Lunar\Models\ProductOptionValue;
use Spatie\LaravelData\Data;

class ColorData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $color,
        public ?string $primaryColor,
        public ?string $secondaryColor,
        public ?string $tertiaryColor,
        public int $position,
    ) {
    }

    public static function fromModel(ProductOptionValue $value): self
    {
        return new self(
            id: $value->id,
            name: $value->translate('name'),
            color: $value->color,
            primaryColor: $value->primary_color,
            secondaryColor: $value->secondary_color,
            tertiaryColor: $value->tertiary_color,
            position: $value->position,
        );
    }
}
