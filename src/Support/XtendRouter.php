<?php

namespace XtendPackages\RESTPresenter\Support;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class XtendRouter extends Router
{
    public function resources(array $resources, array $options = []): void
    {
        foreach ($resources as $name => $controller) {
            $this->resource($name, $controller, $options);
        }
    }

    public function resource($name, $controller, array $options = []): void
    {
        $namespace = config('rest-presenter.generator.namespace');
        $xtendController = Str::of($controller)->replace('XtendPackages\RESTPresenter', $namespace)->value();
        $extendControllerFile = Str::of($controller)->replace('XtendPackages\RESTPresenter', '')
            ->replace('\\', '/')
            ->prepend(app()->path('Api'))
            ->append('.php');

        $controller = file_exists($extendControllerFile) ? $xtendController : $controller;
        Route::apiResource($name, $controller);
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
