<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Concerns;

trait HasAvailability
{
    public function getAvailability(): ?string
    {
        return $this->isAvailable() ? 'available' : ($this->isPreOrder() ? 'pre-order' : 'unavailable');
    }

    public function isAvailable(): bool
    {
        $stock = $this->model->stock ?? 0;

        return $stock > 0 && $stock < 9999;
    }

    public function isPreOrder(): bool
    {
        $stock = $this->model->stock ?? 0;

        return $stock >= 9999;
    }
}
