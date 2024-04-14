<?php

namespace XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Actions;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use XtendPackages\RESTPresenter\Data\Response\DefaultResponse;
use XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Concerns\WithSanctumRateLimit;
use XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Data\Request\LoginDataRequest;

class Login
{
    use WithSanctumRateLimit;

    public static string $method = 'POST';

    public function __invoke(LoginDataRequest $request): JsonResponse
    {
        $this->authenticate($request);
        $user = auth()->user();

        return response()->json([
            'token' => $user->createToken(
                name: config('rest-presenter.auth.token_name'),
                abilities: config('rest-presenter.auth.abilities'),
            )->plainTextToken,
            'user' => DefaultResponse::from($user),
        ]);
    }

    protected function authenticate(LoginDataRequest $request): void
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
