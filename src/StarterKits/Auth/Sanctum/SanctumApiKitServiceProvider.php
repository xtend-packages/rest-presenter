<?php

namespace XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use XtendPackages\RESTPresenter\StarterKits\StarterKitsServiceProvider;

class SanctumApiKitServiceProvider extends StarterKitsServiceProvider
{
    public function register(): void
    {
        $this->ensurePersonalAccessTokensHasExpiresAtColumn();
    }

    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }

    protected function ensurePersonalAccessTokensHasExpiresAtColumn(): void
    {
        if (Schema::hasTable('personal_access_tokens') && ! Schema::hasColumn('personal_access_tokens', 'expires_at')) {
            Schema::table('personal_access_tokens', function (Blueprint $table) {
                $table->timestamp('expires_at')->after('last_used_at')->nullable();
            });
        }
    }
}
