<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar;

use XtendPackages\RESTPresenter\StarterKits\StarterKitsServiceProvider;

class LunarApiKitServiceProvider extends StarterKitsServiceProvider
{
    public function register(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Routes/api.php');
    }
}
