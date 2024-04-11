<?php

namespace XtendPackages\RESTPresenter\Concerns;

use Illuminate\Support\Collection;

trait WithResourceRouteActions
{
    public Collection $routeActions;

    public static bool $onlyRegisterActionRoutes = false;

    protected function getRouteActions(): array
    {
        return method_exists($this, 'routeActions') ? $this->routeActions() : [];
    }
}
