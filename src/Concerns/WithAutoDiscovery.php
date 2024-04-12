<?php

namespace XtendPackages\RESTPresenter\Concerns;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;
use XtendPackages\RESTPresenter\Resources\ResourceController;

trait WithAutoDiscovery
{
    protected function autoDiscover(string $path, $isKit = false): void
    {
        $fileSystem = app(Filesystem::class);
        if (! $fileSystem->isDirectory($path)) {
            return;
        }

        collect($fileSystem->allFiles($path))
            ->filter(fn (SplFileInfo $file) => Str::endsWith($file->getFilename(), 'ResourceController.php'))
            ->mapWithKeys(function (SplFileInfo $file) {
                $name = Str::of(basename($file->getRelativePath()))
                    ->replace('/', '.')
                    ->kebab()
                    ->value();

                $controller = Str::of($file->getRealPath())
                    ->replace(app()->path('Api'), config('rest-presenter.generator.namespace'))
                    ->replace('/', '\\')
                    ->replace('.php', '')
                    ->value();

                return [
                    $name => $controller,
                ];
            })
            ->each(function ($controller, $name) use ($isKit) {
                $kit = Str::of($controller)
                    ->remove(config('rest-presenter.generator.namespace') . '\\StarterKits\\')
                    ->remove('Auth\\')
                    ->before('\\')
                    ->lower()
                    ->value();

                return Route::name($isKit ? $kit . '.' : null)
                    ->prefix($isKit ? $kit : null)
                    ->group(function () use ($name, $controller) {
                        $this->isResourceOnlyActionRoutes($controller)
                            ? $this->registerActionRoutes($controller)
                            : $this->registerResourceRoutes($name, $controller);
                    });
            });
    }

    private function registerActionRoutes(string $controller): void
    {
        $resource = $this->getXtendResourceController($controller);

        collect($resource->routeActions())
            ->each(function ($controller, $name) {
                return Route::match($controller::$method, $name, $controller)
                    ->middleware($controller::$middleware ?? [])
                    ->name($name);
            });
    }

    private function registerResourceRoutes(string $name, string $controller): void
    {
        $this->resource($name, $controller);
    }

    private function isResourceOnlyActionRoutes(string $controller): bool
    {
        return $this->getXtendResourceControllerClass($controller)::$onlyRegisterActionRoutes;
    }

    private function getXtendResourceController(string $controller): ResourceController
    {
        return resolve($this->getXtendResourceControllerClass($controller), [
            'request' => request(),
            'init' => false,
        ]);
    }

    private function getXtendResourceControllerClass(string $controller): string | ResourceController
    {
        return Str::of($controller)
            ->replace(config('rest-presenter.generator.namespace'), 'XtendPackages\\RESTPresenter')
            ->value();
    }
}
