<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Concerns;

trait InteractsWithConfig
{
    // @phpstan-ignore-next-line
    protected function config(string $key, mixed $default = null)
    {
        return config($key, $default) ?? $default;
    }
}
