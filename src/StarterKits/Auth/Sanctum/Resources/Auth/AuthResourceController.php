<?php

namespace XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Resources\Auth;

use XtendPackages\RESTPresenter\Resources\ResourceController;
use XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Actions;

class AuthResourceController extends ResourceController
{
    public static bool $onlyRegisterActionRoutes = true;

    public function routeActions(): array
    {
        return [
            'register' => Actions\Register::class,
            'login' => Actions\Login::class,
            'logout' => Actions\Logout::class,
            'reset-password' => Actions\ResetPassword::class,
            'verify-email' => Actions\VerifyEmail::class,
        ];
    }
}
