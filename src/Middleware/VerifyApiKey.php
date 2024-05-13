<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Middleware;

use Closure;
use Illuminate\Http\Request;

final class VerifyApiKey
{
    public function handle(Request $request, Closure $next): mixed
    {
        $enabled = type(config('rest-presenter.auth.enable_api_key'))->asBool();
        $headerKey = type(config('rest-presenter.auth.key_header'))->asString();

        if ($request->header($headerKey) !== config('rest-presenter.auth.key') && $enabled) {
            abort(403, 'Invalid API key');
        }

        return $next($request);
    }
}
