<?php

namespace XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Actions;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use XtendPackages\RESTPresenter\Models\User;
use XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Data\Request\LoginDataRequest;

class Authenticate
{
    public function execute(LoginDataRequest $request): User
    {
        $this->ensureIsNotRateLimited($request);

        if (! Auth::attempt($request->only('email', 'password')->toArray(), $request->boolean('remember'))) {
            RateLimiter::hit($request->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(LoginDataRequest $request): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        event(new Lockout($request));

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(LoginDataRequest $request): string
    {
        return Str::transliterate(Str::lower($request->email) . '|' . $request->ip());
    }
}
