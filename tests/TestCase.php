<?php

namespace XtendPackages\RESTPresenter\Tests;

use Laravel\Sanctum\SanctumServiceProvider;
use Spatie\LaravelData\LaravelDataServiceProvider;
use XtendPackages\RESTPresenter\Models\User;
use XtendPackages\RESTPresenter\RESTPresenterServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

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

        $app['config']->set('data.date_format', 'Y-m-d\TH:i:s.u\Z');
        $app['config']->set('rest-presenter.resources.user.model', User::class);
    }

    protected function getPackageProviders($app)
    {
        return [
            SanctumServiceProvider::class,
            RESTPresenterServiceProvider::class,
            LaravelDataServiceProvider::class,
        ];
    }
}
