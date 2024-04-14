<?php

namespace XtendPackages\RESTPresenter\Support;

use Illuminate\Routing\PendingResourceRegistration;
use Illuminate\Routing\Router;
use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use XtendPackages\RESTPresenter\Concerns\WithAutoDiscovery;
use XtendPackages\RESTPresenter\Resources\Users\UserResourceController;

class XtendRouter extends Router
{
    use WithAutoDiscovery;

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

        $this->resource('users', UserResourceController::class);

        $this->autoDiscoverResources();
        $this->autoDiscoverStarterKits();
    }

    public function autoDiscoverResources(): void
    {
        $this->autoDiscover(
            path: app()->basePath(config('rest-presenter.generator.path') . '/Resources'),
        );
    }

    public function autoDiscoverStarterKits(): void
    {
        $this->autoDiscover(
            path: app()->basePath(config('rest-presenter.generator.path') . '/StarterKits'),
            isKit: true,
        );
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
