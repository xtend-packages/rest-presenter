<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Sanctum\Resources\Auth;

use XtendPackages\RESTPresenter\Resources\ResourceController;
use XtendPackages\RESTPresenter\StarterKits\Sanctum\Actions;

class AuthResourceController extends ResourceController
{
    public static bool $onlyRegisterActionRoutes = true;

    /**
     * @return array<string, string>
     */
    public function routeActions(): array
    {
        return [
            'register' => \XtendPackages\RESTPresenter\StarterKits\Sanctum\Actions\Register::class,
            'login' => \XtendPackages\RESTPresenter\StarterKits\Sanctum\Actions\Login::class,
            'logout' => \XtendPackages\RESTPresenter\StarterKits\Sanctum\Actions\Logout::class,
            'reset-password' => \XtendPackages\RESTPresenter\StarterKits\Sanctum\Actions\ResetPassword::class,
        ];
    }
}
