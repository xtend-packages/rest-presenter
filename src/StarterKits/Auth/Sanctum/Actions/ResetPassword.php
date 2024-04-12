<?php

namespace XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Actions;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Data\Request\ResetPasswordDataRequest;

class ResetPassword
{
    public static string $method = 'POST';

    protected string $message = '';

    public function __invoke(ResetPasswordDataRequest $request): JsonResponse
    {
        $this->sendResetLink($request->email);

        return response()->json([
            'message' => $this->message,
        ]);
    }

    protected function sendResetLink(string $email): void
    {
        $status = Password::sendResetLink(
            credentials: ['email' => $email],
            callback: function (User $user, $token) {
                $this->setTemporaryPassword($user, $token);
                $user->sendPasswordResetNotification($token);
            },
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->message = __($status);
            return;
        }

        $this->message = __('rest-presenter::auth.reset_password_message');
    }

    protected function setTemporaryPassword(User $user, string $token): void
    {
        $user->password = bcrypt(self::generateTemporaryPassword($user, $token));
        $user->save();
    }

    public static function generateTemporaryPassword(User $user, string $token): string
    {
        return 'temp-' . Str::take($token, 5) . '-' . Str::padLeft($user->id, 4, '0');
    }
}
