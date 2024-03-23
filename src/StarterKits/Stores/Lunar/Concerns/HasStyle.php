<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Concerns;

use Lunar\Models\Collection;

trait HasStyle
{
    public function getStyle(): ?Collection
    {
        return $this->model->collections->first(
            fn (Collection $collection) => $collection->group->handle === 'styles',
        );
    }
}
