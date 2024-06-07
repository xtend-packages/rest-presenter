<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Sanctum\Actions;

use Illuminate\Http\JsonResponse;
use XtendPackages\RESTPresenter\Models\User;

final class Logout
{
    public static string $method = 'GET';

    /**
     * @var array<int, string>
     */
    public static array $middleware = ['auth:sanctum'];

    private string $message = '';

    public function __invoke(): JsonResponse
    {
        config('rest-presenter.auth.logout_revoke_all_tokens')
            ? $this->deleteAllTokens()
            : $this->deleteCurrentToken();

        return response()->json([
            'message' => $this->message,
        ]);
    }

    private function deleteAllTokens(): void
    {
        /** @var User $user */
        $user = type(auth()->user())->as(User::class);

        /** @var \Illuminate\Database\Eloquent\Relations\MorphMany<\Laravel\Sanctum\PersonalAccessToken> $tokens */
        $tokens = $user->tokens();

        $tokens->delete();

        $this->message = __('rest-presenter::auth.logout_message');
    }

    private function deleteCurrentToken(): void
    {
        /** @var User $user */
        $user = type(auth()->user())->as(User::class);

        /** @var \Laravel\Sanctum\Contracts\HasAbilities $currentAccessToken */
        $currentAccessToken = $user->currentAccessToken();

        $currentAccessToken->delete(); // @phpstan-ignore-line

        $this->message = __('rest-presenter::auth.logout_device_message');
    }
}
