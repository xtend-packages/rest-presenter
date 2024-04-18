<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar;

use Illuminate\Support\ServiceProvider;

final class LunarApiKitServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->loadRoutesFrom(__DIR__.'/Routes/api.php');
    }
}
