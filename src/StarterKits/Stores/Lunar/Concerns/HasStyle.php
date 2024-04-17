<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Concerns;

use Lunar\Models\Collection;

trait HasStyle
{
    public function getStyle(): ?Collection
    {
        return $this->model->collections->first(
            fn (Collection $collection): bool => $collection->group->handle === 'styles',
        );
    }
}
