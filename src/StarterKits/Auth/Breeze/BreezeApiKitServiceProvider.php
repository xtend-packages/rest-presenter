<?php

namespace XtendPackages\RESTPresenter\StarterKits\Auth\Breeze;

use Illuminate\Auth\Notifications\ResetPassword;
use XtendPackages\RESTPresenter\StarterKits\StarterKitsServiceProvider;

class BreezeApiKitServiceProvider extends StarterKitsServiceProvider
{
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
