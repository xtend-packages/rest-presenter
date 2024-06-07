<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Sanctum;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

final class SanctumApiKitServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->ensurePersonalAccessTokensHasExpiresAtColumn();
    }

    public function boot(): void
    {
        // @todo: Move this to mailable class and support translations
        ResetPassword::toMailUsing(static fn ($notifiable, string $token) => (new MailMessage)
            ->subject(Lang::get('Reset Password Notification')) // @phpstan-ignore-line
            ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
            ->action(Lang::get('Login'), url(config('app.frontend_url'))) // @phpstan-ignore-line
            ->line(Lang::get('Please use the following temporary password to login to your account: :password', [
                'password' => Actions\ResetPassword::generateTemporaryPassword($notifiable, $token), // @phpstan-ignore-line
            ]))
            ->line(Lang::get('If you did not request a password reset, no further action is required.')));
    }

    private function ensurePersonalAccessTokensHasExpiresAtColumn(): void
    {
        if (! Schema::hasTable('personal_access_tokens')) {
            return;
        }
        if (Schema::hasColumn('personal_access_tokens', 'expires_at')) {
            return;
        }
        Schema::table('personal_access_tokens', function (Blueprint $table): void {
            $table->timestamp('expires_at')->after('last_used_at')->nullable();
        });
    }
}
