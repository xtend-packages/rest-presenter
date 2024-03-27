<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Data\Response;

use Lunar\Base\Casts\Price as CastsPrice;
use Lunar\Models\Price;
use Spatie\LaravelData\Data;

class PriceData extends Data
{
    public function __construct(
        public int $id,
        public ?string $customerGroupId,
        public int $currencyId,
        public int $price,
        public ?int $comparePrice,
        public ?int $tier,
    ) {
    }

    public static function fromModel(Price $price): self
    {
        return new self(
            id: $price->id,
            customerGroupId: $price->customer_group_id,
            currencyId: $price->currency_id,
            price: $price->price->value ?? 0,
            comparePrice: $price->compare_price->value ?? 0,
            tier: $price->tier,
        );
    }
}
