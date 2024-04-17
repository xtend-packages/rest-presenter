<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Concerns;

use Lunar\Models\Price;

trait HasPrices
{
    public function getPrice(): Price
    {
        return $this->model->prices->first();
    }
}
