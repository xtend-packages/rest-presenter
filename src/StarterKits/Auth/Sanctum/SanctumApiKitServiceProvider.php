<?php

namespace XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
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
        // @todo: Move this to mailable class and support translations
        ResetPassword::toMailUsing(function (User $notifiable, string $token) {
            return (new MailMessage)
                ->subject(Lang::get('Reset Password Notification'))
                ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
                ->action(Lang::get('Login'), url(config('app.frontend_url')))
                ->line(Lang::get('Please use the following temporary password to login to your account: :password', [
                    'password' => Actions\ResetPassword::generateTemporaryPassword($notifiable, $token),
                ]))
                ->line(Lang::get('If you did not request a password reset, no further action is required.'));
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
