<?php

namespace XtendPackages\RESTPresenter\StarterKits\Auth\Breeze;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use XtendPackages\RESTPresenter\StarterKits\StarterKitsServiceProvider;

class BreezeApiKitServiceProvider extends StarterKitsServiceProvider
{
    public function register(): void
    {
        Route::macro('xtendAuthResource', function (string $httpVerb, string $uri, string $controller, string $name, ?array $middleware = null) {
            $namespace = config('rest-presenter.generator.namespace');
            $xtendController = Str::of($controller)->replace('XtendPackages\RESTPresenter', $namespace)->value();
            $controller = class_exists($xtendController) ? $xtendController : $controller;

            Route::match([$httpVerb], $uri, [$controller, 'store'])
                ->middleware($middleware)
                ->name($name);
        });
    }

    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
