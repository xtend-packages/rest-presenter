<?php

namespace XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Actions;

use Illuminate\Http\JsonResponse;

class Logout
{
    public static string $method = 'GET';

    public function __invoke(): JsonResponse
    {
        config('rest-presenter.auth.logout_revoke_all_tokens')
            ? $this->deleteAllTokens()
            : $this->deleteCurrentToken();

        return response()->json([
            'message' => __('auth.logout'),
        ]);
    }

    protected function deleteAllTokens(): void
    {
        /** @var \Illuminate\Database\Eloquent\Relations\MorphMany $tokens */
        $tokens = auth()->user()->tokens();

        $tokens->delete();
    }

    protected function deleteCurrentToken(): void
    {
        /** @var \Laravel\Sanctum\Contracts\HasAbilities $currentAccessToken */
        $currentAccessToken = auth()->user()->currentAccessToken();

        $currentAccessToken->delete();
    }
}
