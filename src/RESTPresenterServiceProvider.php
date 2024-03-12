<?php

namespace XtendPackages\RESTPresenter;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class RESTPresenterServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('rest-presenter')
            ->hasViews()
            ->hasConfigFile();
    }
}
