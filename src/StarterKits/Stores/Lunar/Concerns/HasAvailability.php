<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Concerns;

trait HasAvailability
{
    public function getAvailability(): ?string
    {
        return $this->isAvailable() ? 'available' : ($this->isPreOrder() ? 'pre-order' : 'unavailable');
    }

    public function isAvailable(): bool
    {
        return $this->model->stock > 0  && $this->model->stock < 9999;
    }

    public function isPreOrder(): bool
    {
        return $this->model->stock >= 9999;
    }
}
