<?php

namespace XtendPackages\RESTPresenter\Support;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\PendingResourceRegistration;
use Illuminate\Routing\Router;
use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;
use XtendPackages\RESTPresenter\Resources\Users\UserResourceController;

class XtendRouter extends Router
{
    public function register(): RouteRegistrar
    {
        $prefix = config('rest-presenter.api.prefix');
        $version = config('rest-presenter.api.version');

        return Route::name($prefix . '.' . $version . '.')
            ->prefix($prefix . '/' . $version)
            ->middleware(config('rest-presenter.api.middleware'))
            ->group(fn () => $this->routes());
    }

    public function routes(): void
    {
        Route::get('resources', function () {
            return collect(Route::getRoutes())->map(fn (\Illuminate\Routing\Route $route) => [
                'uri' => $route->uri,
                'methods' => $route->methods,
                'name' => $route->action['as'] ?? null,
                'action' => $route->action['uses'] ?? null,
                'middleware' => $route->action['middleware'] ?? null,
            ])->filter(
                fn ($route) => Str::startsWith($route['uri'], 'api/v1'),
            )->values();
        })->name('resources');

        Route::middleware('auth:sanctum')->group(function () {
            $this->resource('users', UserResourceController::class);
        });

        $this->autoDiscoverResources();
    }

    public function autoDiscoverResources(): void
    {
        $fileSystem = app(Filesystem::class);
        if (! $fileSystem->isDirectory(app()->basePath(config('rest-presenter.generator.path') . '/Resources/Custom'))) {
            return;
        }

        collect($fileSystem->allFiles(app()->basePath(config('rest-presenter.generator.path') . '/Resources/Custom')))
            ->filter(fn (SplFileInfo $file) => Str::endsWith($file->getFilename(), 'ResourceController.php'))
            ->mapWithKeys(function (SplFileInfo $file) {
                $name = Str::of($file->getRelativePath())->snake('-')->value();
                $controller = Str::of($file->getRealPath())
                    ->replace(app()->path('Api'), config('rest-presenter.generator.namespace'))
                    ->replace('/', '\\')
                    ->replace('.php', '')
                    ->value();

                return [
                    $name => $controller,
                ];
            })
            ->each(fn ($controller, $name) => $this->resource($name, $controller));
    }

    public function resources(array $resources, array $options = []): void
    {
        foreach ($resources as $name => $controller) {
            $this->resource($name, $controller, $options);
        }
    }

    public function resource($name, $controller, array $options = []): PendingResourceRegistration
    {
        $namespace = config('rest-presenter.generator.namespace');
        $xtendController = Str::of($controller)->replace('XtendPackages\RESTPresenter', $namespace)->value();
        $extendControllerFile = Str::of($controller)->replace('XtendPackages\RESTPresenter', '')
            ->replace('\\', '/')
            ->prepend(app()->path('Api'))
            ->append('.php');

        $controller = file_exists($extendControllerFile) ? $xtendController : $controller;

        return Route::apiResource($name, $controller);
    }

    public function auth(string $httpVerb, string $uri, string $controller, string $name, ?array $middleware = null): void
    {
        $namespace = config('rest-presenter.generator.namespace');
        $xtendController = Str::of($controller)->replace('XtendPackages\RESTPresenter', $namespace)->value();
        $extendControllerFile = Str::of($controller)->replace('XtendPackages\RESTPresenter', '')
            ->replace('\\', '/')
            ->prepend(app()->path('Api'))
            ->append('.php');
        $controller = file_exists($extendControllerFile) ? $xtendController : $controller;

        Route::match([$httpVerb], $uri, [$controller, 'store'])
            ->middleware($middleware)
            ->name($name);
    }
}
