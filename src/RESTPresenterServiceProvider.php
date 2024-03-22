<?php

namespace XtendPackages\RESTPresenter;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use XtendPackages\RESTPresenter\Base\RESTPresenter;
use XtendPackages\RESTPresenter\Commands\RESTPresenterSetupCommand;
use XtendPackages\RESTPresenter\StarterKits\Auth\Breeze\BreezeApiServiceProvider;

class RESTPresenterServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('rest-presenter')
            ->hasRoute('api')
            ->hasViews()
            ->hasConfigFile()
            ->publishesServiceProvider('XtendRESTPresenterServiceProvider')
            ->hasCommands([
                RESTPresenterSetupCommand::class,
            ]);
    }

    public function registeringPackage()
    {
        $this->app->singleton('rest-presenter', function () {
            return new RESTPresenter();
        });
    }
}
