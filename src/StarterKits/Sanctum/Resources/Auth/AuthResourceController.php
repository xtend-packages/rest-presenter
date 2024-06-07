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
            'register' => Actions\Register::class,
            'login' => Actions\Login::class,
            'logout' => Actions\Logout::class,
            'reset-password' => Actions\ResetPassword::class,
        ];
    }
}
