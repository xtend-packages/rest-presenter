<?php

use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use XtendPackages\RESTPresenter\Resources\Users\UserResourceController;

Route::name('api.v1.')->prefix('api.v1')
    ->middleware([
        // \XtendPackages\RESTPresenter\Middleware\VerifyApiKey::class,
        SubstituteBindings::class,
    ])
    ->group(function () {
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
            Route::apiResource('users', UserResourceController::class);
        });
    });
