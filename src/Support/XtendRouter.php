<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Support;

use Illuminate\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Routing\PendingResourceRegistration;
use Illuminate\Routing\Router;
use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use XtendPackages\RESTPresenter\Concerns\WithAutoDiscovery;
use XtendPackages\RESTPresenter\Resources\Users\UserResourceController;

final class XtendRouter extends Router
{
    use WithAutoDiscovery;

    public function __construct(Dispatcher $events, ?Container $container = null)
    {
        parent::__construct($events, $container);
    }

    public function register(): RouteRegistrar
    {
        $prefix = config('rest-presenter.api.prefix');
        $version = config('rest-presenter.api.version');

        return Route::name($prefix.'.'.$version.'.')
            ->prefix($prefix.'/'.$version)
            ->middleware(config('rest-presenter.api.middleware') ?? []) // @phpstan-ignore-line
            ->group(fn () => $this->routes());
    }

    public function routes(): void
    {
        Route::get('resources', function () {
            /** @var \Illuminate\Routing\Route[] $routes */
            $routes = Route::getRoutes();

            return collect($routes)->map(fn (\Illuminate\Routing\Route $route): array => [
                'uri' => $route->uri,
                'methods' => $route->methods,
                'name' => $route->action['as'] ?? null,
                'action' => $route->action['uses'] ?? null,
                'middleware' => $route->action['middleware'] ?? null,
            ])
                ->filter(
                    fn ($route) => Str::startsWith($route['uri'], 'api/v1'),
                )
                ->filter(
                    fn ($route): bool => $route['methods'][0] === 'GET' || ! Str::of($route['uri'])->contains('filament'),
                )
                ->values();
        })->name('resources');

        $this->resource('users', UserResourceController::class);

        $this->autoDiscoverResources();
        $this->autoDiscoverStarterKits();
    }

    public function autoDiscoverResources(): void
    {
        $this->autoDiscover(
            path: app()->basePath(config('rest-presenter.generator.path').'/Resources'),
        );
    }

    public function autoDiscoverStarterKits(): void
    {
        $this->autoDiscover(
            path: app()->basePath(config('rest-presenter.generator.path').'/StarterKits'),
            isKit: true,
        );
    }

    /**
     * @param  array<string>  $resources
     * @param  array<string>  $options
     */
    public function resources(array $resources, array $options = []): void
    {
        foreach ($resources as $name => $controller) {
            $this->resource($name, $controller, $options);
        }
    }

    /**
     * @param  array<string>  $options
     */
    public function resource($name, $controller, array $options = []): PendingResourceRegistration
    {
        /** @var string $namespace */
        $namespace = config('rest-presenter.generator.namespace');
        $xtendController = Str::of($controller)->replace('XtendPackages\RESTPresenter', $namespace)->value();
        $extendControllerFile = Str::of($controller)->replace('XtendPackages\RESTPresenter', '')
            ->replace('\\', '/')
            ->prepend(app()->path('Api'))
            ->append('.php')
            ->value();

        $controller = file_exists($extendControllerFile) ? $xtendController : $controller;
        $authenticated = $controller::$isAuthenticated ?? false;

        return Route::apiResource($name, $controller)->middleware($authenticated ? ['auth:sanctum'] : []);
    }

    /**
     * @param  array<string>|null  $middleware
     */
    public function auth(string $httpVerb, string $uri, string $controller, string $name, ?array $middleware = null): void
    {
        /** @var string $namespace */
        $namespace = config('rest-presenter.generator.namespace');
        $xtendController = Str::of($controller)->replace('XtendPackages\RESTPresenter', $namespace)->value();
        $extendControllerFile = Str::of($controller)->replace('XtendPackages\RESTPresenter', '')
            ->replace('\\', '/')
            ->prepend(app()->path('Api'))
            ->append('.php')
            ->value();

        $controller = file_exists($extendControllerFile) ? $xtendController : $controller;

        Route::match([$httpVerb], $uri, [$controller, 'store'])
            ->name($name)
            ->middleware($middleware);
    }
}
