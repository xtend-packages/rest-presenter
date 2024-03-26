<?php

namespace XtendPackages\RESTPresenter\Tests;

use Laravel\Sanctum\SanctumServiceProvider;
use Spatie\LaravelData\LaravelDataServiceProvider;
use XtendPackages\RESTPresenter\Models\User;
use XtendPackages\RESTPresenter\RESTPresenterServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use XtendPackages\RESTPresenter\StarterKits\Auth\Breeze\BreezeApiKitServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations(['--database' => 'testbench']);
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('data.date_format', 'Y-m-d H:i:s');
        $app['config']->set('rest-presenter.resources.user.model', User::class);
    }

    protected function getPackageProviders($app): array
    {
        return [
            SanctumServiceProvider::class,
            RESTPresenterServiceProvider::class,
            BreezeApiKitServiceProvider::class,
            LaravelDataServiceProvider::class,
        ];
    }
}
