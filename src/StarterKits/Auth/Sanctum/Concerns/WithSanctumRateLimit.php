<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Concerns;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use XtendPackages\RESTPresenter\StarterKits\Auth\Sanctum\Data\Request\LoginDataRequest;

trait WithSanctumRateLimit
{
    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(LoginDataRequest $request): string
    {
        $ipAddress = request()->ip();

        return Str::transliterate(Str::lower($request->email).'|'.$ipAddress);
    }

    protected function ensureIsNotRateLimited(LoginDataRequest $request): void
    {
        $maxAttempts = type(config('rest-presenter.auth.rate_limit.max_attempts'))->asInt();

        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), $maxAttempts)) {
            return;
        }

        $httpRequest = new Request($request->toArray());
        event(new Lockout($httpRequest));

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }
}
