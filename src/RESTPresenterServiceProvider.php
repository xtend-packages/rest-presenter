<?php

namespace XtendPackages\RESTPresenter;

use Illuminate\Foundation\Application;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use XtendPackages\RESTPresenter\Base\RESTPresenter;
use XtendPackages\RESTPresenter\Commands\Generator\MakeController;
use XtendPackages\RESTPresenter\Commands\Generator\MakePresenter;
use XtendPackages\RESTPresenter\Commands\Generator\MakeResource;
use XtendPackages\RESTPresenter\Commands\RESTPresenterSetupCommand;
use XtendPackages\RESTPresenter\Commands\XtendStarterKit;
use XtendPackages\RESTPresenter\Facades\XtendRoute;

class RESTPresenterServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('rest-presenter')
            ->hasViews()
            ->hasConfigFile()
            ->publishesServiceProvider('RESTPresenterServiceProvider')
            ->hasCommands([
                RESTPresenterSetupCommand::class,
                XtendStarterKit::class,
                MakeResource::class,
                MakePresenter::class,
                MakeController::class,
            ]);
    }

    public function registeringPackage(): void
    {
        $this->app->singleton('rest-presenter', function () {
            return new RESTPresenter();
        });

        $this->app->bind('xtend-router', function (Application $app) {
            return new Support\XtendRouter($app['events'], $app);
        });

        XtendRoute::register();
    }

    public function provides():array
    {
        return [
            'rest-presenter',
            'xtend-router',
        ];
    }
}
