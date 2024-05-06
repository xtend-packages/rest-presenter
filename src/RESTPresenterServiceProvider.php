<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter;

use Illuminate\Foundation\Application;
use InvalidArgumentException;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use XtendPackages\RESTPresenter\Base\RESTPresenter;
use XtendPackages\RESTPresenter\Commands\GenerateApiCollection;
use XtendPackages\RESTPresenter\Commands\Generator\MakeController;
use XtendPackages\RESTPresenter\Commands\Generator\MakeData;
use XtendPackages\RESTPresenter\Commands\Generator\MakeFilter;
use XtendPackages\RESTPresenter\Commands\Generator\MakePresenter;
use XtendPackages\RESTPresenter\Commands\Generator\MakeResource;
use XtendPackages\RESTPresenter\Commands\RESTPresenterSetupCommand;
use XtendPackages\RESTPresenter\Commands\XtendStarterKit;
use XtendPackages\RESTPresenter\Facades\XtendRoute;
use XtendPackages\RESTPresenter\Support\ApiCollection\Exporters;
use XtendPackages\RESTPresenter\Support\ApiCollection\Exporters\ExporterContract;
use XtendPackages\RESTPresenter\Support\XtendRouter;

final class RESTPresenterServiceProvider extends PackageServiceProvider
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
                GenerateApiCollection::class,
            ]);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton('rest-presenter', fn (): RESTPresenter => new RESTPresenter());

        $this->app->bind('xtend-router', fn (Application $app): XtendRouter => new XtendRouter($app['events'], $app));

        $this->app->bind(ExporterContract::class, function (): Exporters\BaseExporter {
            $provider = type(config('rest-presenter.exporters.provider'))->asString();

            $exporter = match ($provider) {
                'insomnia' => Exporters\Insomnia\Exporter::class,
                'postman' => Exporters\Postman\Exporter::class,
                default => throw new InvalidArgumentException('Invalid exporter provider'),
            };

            $config = type(config('rest-presenter.exporters.'.$provider))->asArray();

            return new $exporter($config);
        });

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
