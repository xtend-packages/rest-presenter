<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Sanctum\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use XtendPackages\RESTPresenter\Data\Response\DefaultResponse;
use XtendPackages\RESTPresenter\Models\User;
use XtendPackages\RESTPresenter\StarterKits\Sanctum\Concerns\WithSanctumRateLimit;
use XtendPackages\RESTPresenter\StarterKits\Sanctum\Data\Request\LoginDataRequest;

final class Login
{
    use WithSanctumRateLimit;

    public static string $method = 'POST';

    public function __invoke(LoginDataRequest $request): JsonResponse
    {
        $this->authenticate($request);
        $user = type(auth()->user())->as(User::class);

        $config = [
            'abilities' => type(config('rest-presenter.auth.abilities'))->asArray(),
            'tokenName' => type(config('rest-presenter.auth.token_name'))->asString(),
        ];

        return response()->json([
            'token' => $user->createToken(
                name: $config['tokenName'],
                abilities: $config['abilities'],
            )->plainTextToken,
            'user' => DefaultResponse::from($user),
        ]);
    }

    private function authenticate(LoginDataRequest $request): void
    {
        $this->ensureIsNotRateLimited($request);

        if (! Auth::attempt($request->only('email', 'password')->toArray())) {
            RateLimiter::hit($this->throttleKey($request));

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey($request));
    }
}
