<?php

namespace XtendPackages\RESTPresenter\StarterKits\Filament\Tests;

use XtendPackages\RESTPresenter\StarterKits\Filament\FilamentApiKitServiceProvider;
use XtendPackages\RESTPresenter\Tests\TestCase;

class FilamentTestCase extends TestCase
{
    protected function getEnvironmentSetUp($app): void
    {
        parent::getEnvironmentSetUp($app);
    }

    protected function getPackageProviders($app): array
    {
        return array_merge(parent::getPackageProviders($app), [
            FilamentApiKitServiceProvider::class,
        ]);
    }
}
