<?php

namespace XtendPackages\RESTPresenter;

use Illuminate\Foundation\Application;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use XtendPackages\RESTPresenter\Base\RESTPresenter;
use XtendPackages\RESTPresenter\Commands\Generator\MakeController;
use XtendPackages\RESTPresenter\Commands\Generator\MakeData;
use XtendPackages\RESTPresenter\Commands\Generator\MakeFilter;
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
            ->hasTranslations()
            ->publishesServiceProvider('RESTPresenterServiceProvider')
            ->hasCommands([
                RESTPresenterSetupCommand::class,
                XtendStarterKit::class,
                MakeResource::class,
                MakePresenter::class,
                MakeController::class,
                MakeFilter::class,
                MakeData::class,
            ]);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton('rest-presenter', fn(): \XtendPackages\RESTPresenter\Base\RESTPresenter => new RESTPresenter());

        $this->app->bind('xtend-router', fn(Application $app): \XtendPackages\RESTPresenter\Support\XtendRouter => new Support\XtendRouter($app['events'], $app));

        XtendRoute::register();
    }

    /**
     * @return array<string>
     */
    public function provides(): array
    {
        return [
            'rest-presenter',
            'xtend-router',
        ];
    }
}
