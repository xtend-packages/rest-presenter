<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Concerns;

trait WithResourceRouteActions
{
    public static bool $onlyRegisterActionRoutes = false;

    /**
     * @return array<string, string>
     */
    protected function getRouteActions(): array
    {
        return method_exists($this, 'routeActions') ? $this->routeActions() : [];
    }
}
