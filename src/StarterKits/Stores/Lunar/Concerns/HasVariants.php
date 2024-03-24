<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Concerns;

use Illuminate\Support\Collection;
use Lunar\Models\ProductOptionValue;
use Lunar\Models\ProductVariant;

trait HasVariants
{
    protected ?Collection $variants = null;

    public function getColors(): Collection
    {
        return $this->getProductOptions('color');
    }

    public function getSizes(): Collection
    {
        return $this->getProductOptions('size');
    }

    protected function getProductOptions(string $handle): Collection
    {
        /** @var Collection<int, ProductOptionValue> $values */
        $values = $this->getVariants()
            ->flatMap(fn (ProductVariant $variant) => collect($variant->values)->filter(
                fn (ProductOptionValue $value) => $value->option->handle === $handle,
            )->values());

        return $values
            ->unique('id')
            ->values()
            ->sortBy('position');
    }

    protected function getVariants(): Collection
    {
        if ($this->variants === null) {
            $this->variants = $this->model
                ->variants()
                ->get();
        }

        return $this->variants;
    }
}
