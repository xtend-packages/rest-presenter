<?php

namespace XtendPackages\RESTPresenter\Concerns;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;
use XtendPackages\RESTPresenter\Resources\ResourceController;

trait WithAutoDiscovery
{
    protected function autoDiscover(string $path, bool $isKit = false): void
    {
        $fileSystem = app(Filesystem::class);
        if (! $fileSystem->isDirectory($path)) {
            return;
        }

        $namespace = type(config('rest-presenter.generator.namespace'))->asString();

        collect($fileSystem->allFiles($path))
            ->filter(fn (SplFileInfo $file) => Str::endsWith($file->getFilename(), 'ResourceController.php'))
            ->mapWithKeys(function (SplFileInfo $file) use ($namespace): array {
                $name = Str::of(basename($file->getRelativePath()))
                    ->replace('/', '.')
                    ->kebab()
                    ->value();
                $controller = Str::of($file->getRealPath())
                    ->replace(app()->path('Api'), $namespace)
                    ->replace('/', '\\')
                    ->replace('.php', '')
                    ->value();

                return [
                    $name => $controller,
                ];
            })
            ->each(function ($controller, $name) use ($isKit, $namespace) {
                $kit = Str::of($controller)
                    ->remove($namespace . '\\StarterKits\\')
                    ->remove('Auth\\')
                    ->before('\\')
                    ->lower()
                    ->value();

                $routeName = $isKit ? $kit . '.' : null;
                if ($routeName === null || $routeName === '' || $routeName === '0') {
                    return null;
                }

                return Route::name($routeName)
                    ->prefix($kit)
                    ->group(function () use ($name, $controller): void {
                        $this->isResourceOnlyActionRoutes($controller)
                            ? $this->registerActionRoutes($controller)
                            : $this->registerResourceRoutes($name, $controller);
                    });
            });
    }

    private function registerActionRoutes(string $controller): void
    {
        /** @var ResourceController $resource */
        $resource = $this->getXtendResourceController($controller);

        collect($resource->routeActions())
            ->each(fn($controller, $name) => Route::match($controller::$method, $name, $controller) // @phpstan-ignore-line
                ->middleware($controller::$middleware ?? []) // @phpstan-ignore-line
                ->name($name));
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
        $name = type($this->getXtendResourceControllerClass($controller))->asString();
        return resolve($name, [
            'request' => request(),
            'init' => false,
        ]);
    }

    private function getXtendResourceControllerClass(string $controller): string | ResourceController
    {
        $namespace = type(config('rest-presenter.generator.namespace'))->asString();
        return Str::of($controller)
            ->replace($namespace, 'XtendPackages\\RESTPresenter')
            ->value();
    }
}
