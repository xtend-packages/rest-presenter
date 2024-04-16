<?php

namespace XtendPackages\RESTPresenter\Concerns;

use Illuminate\Support\Collection;

trait WithResourceRouteActions
{
    /**
     * @var Collection<string, string> $routeActions
     */
    public Collection $routeActions;

    public static bool $onlyRegisterActionRoutes = false;

    /**
     * @return array<string, string>
     */
    protected function getRouteActions(): array
    {
        return method_exists($this, 'routeActions') ? $this->routeActions() : [];
    }
}
