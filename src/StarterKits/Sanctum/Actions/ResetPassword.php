<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Sanctum\Actions;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use XtendPackages\RESTPresenter\StarterKits\Sanctum\Data\Request\ResetPasswordDataRequest;

final class ResetPassword
{
    public static string $method = 'POST';

    private string $message = '';

    public function __invoke(ResetPasswordDataRequest $request): JsonResponse
    {
        $this->sendResetLink($request->email);

        return response()->json([
            'message' => $this->message,
        ]);
    }

    public static function generateTemporaryPassword(User $user, string $token): string
    {
        return 'temp-'.Str::take($token, 5).'-'.Str::padLeft((string) $user->id, 4, '0');
    }

    private function sendResetLink(string $email): void
    {
        $status = Password::sendResetLink(
            credentials: ['email' => $email],
            callback: function (User $user, $token): void {
                $this->setTemporaryPassword($user, $token);
                $user->sendPasswordResetNotification($token);
            },
        );

        if ($status !== Password::RESET_LINK_SENT) {
            $this->message = __($status);

            return;
        }

        $this->message = __('rest-presenter::auth.reset_password_message');
    }

    private function setTemporaryPassword(User $user, string $token): void
    {
        $user->password = bcrypt(self::generateTemporaryPassword($user, $token)); // @phpstan-ignore-line
        $user->save();
    }
}
