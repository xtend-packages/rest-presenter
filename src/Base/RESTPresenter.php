<?php

namespace XtendPackages\RESTPresenter\Base;

use Illuminate\Contracts\Foundation\Application;

class RESTPresenter
{
    protected Application $app;

    public function register(Application $app): self
    {
        $this->app = $app;

        return $this;
    }

    public function starterKits(array $starterKits): self
    {
        foreach ($starterKits as $starterKit) {
            $this->registerKit($starterKit);
        }

        return $this;
    }

    protected function registerKit($kit): void
    {
        if (! $this->isKitRegistered($kit)) {
            $this->app->register($kit);
        }
    }

    protected function isKitRegistered(string $kit): bool
    {
        return $this->app->providerIsLoaded($kit);
    }
}
