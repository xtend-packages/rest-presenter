<?php

namespace XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Resources;

use XtendPackages\RESTPresenter\Resources\ResourceController;
use XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Actions;

class AuthResourceController extends ResourceController
{
    public function actions(): array
    {
        return [
            'authenticated' => Actions\Authenticate::class,
            'register' => Actions\Register::class,
            'logout' => Actions\Logout::class,
            'forgot-password' => Actions\ForgotPassword::class,
            'verify-email' => Actions\VerifyEmail::class,
        ];
    }
}
