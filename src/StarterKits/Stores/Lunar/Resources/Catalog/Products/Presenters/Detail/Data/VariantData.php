<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Detail\Data;

use Illuminate\Support\Collection;
use Lunar\Models\ProductVariant;
use Spatie\LaravelData\Data;

class VariantData extends Data
{
    public function __construct(
        public int $id,
        public bool $base,
        public bool $primary,
        public Collection | array | null $attributeData,
        public string $sku,
        public float $weightValue,
        public int $stock,
    ) {
    }

    public static function fromModel(ProductVariant $variant): self
    {
        return new self(
            id: $variant->id,
            base: $variant->base,
            primary: $variant->primary,
            attributeData: $variant->attribute_data,
            sku: $variant->sku,
            weightValue: $variant->weight_value,
            stock: $variant->stock,
        );
    }
}
