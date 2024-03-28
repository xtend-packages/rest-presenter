<?php

namespace XtendPackages\RESTPresenter\Tests;

use Laravel\Sanctum\SanctumServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\LaravelData\LaravelDataServiceProvider;
use XtendPackages\RESTPresenter\Facades\XtendRoute;
use XtendPackages\RESTPresenter\Models\User;
use XtendPackages\RESTPresenter\RESTPresenterServiceProvider;

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

        XtendRoute::register();
    }

    protected function getPackageProviders($app): array
    {
        return [
            SanctumServiceProvider::class,
            RESTPresenterServiceProvider::class,
            LaravelDataServiceProvider::class,
        ];
    }
}
