<?php

namespace XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Actions;

use Illuminate\Http\JsonResponse;

class Logout
{
    public static string $method = 'GET';

    public static array $middleware = ['auth:sanctum'];

    protected string $message = '';

    public function __invoke(): JsonResponse
    {
        config('rest-presenter.auth.logout_revoke_all_tokens')
            ? $this->deleteAllTokens()
            : $this->deleteCurrentToken();

        return response()->json([
            'message' => $this->message,
        ]);
    }

    protected function deleteAllTokens(): void
    {
        /** @var \Illuminate\Database\Eloquent\Relations\MorphMany $tokens */
        $tokens = auth()->user()->tokens();

        $tokens->delete();

        $this->message = __('rest-presenter::auth.logout_message');
    }

    protected function deleteCurrentToken(): void
    {
        /** @var \Laravel\Sanctum\Contracts\HasAbilities $currentAccessToken */
        $currentAccessToken = auth()->user()->currentAccessToken();

        $currentAccessToken->delete();

        $this->message = __('rest-presenter::auth.logout_device_message');
    }
}
