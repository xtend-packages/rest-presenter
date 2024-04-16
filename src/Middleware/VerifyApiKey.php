<?php

namespace XtendPackages\RESTPresenter\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyApiKey
{
    public function handle(Request $request, Closure $next): mixed
    {
        $headerKey = type(config('rest-presenter.auth.key_header'))->asString();

        if ($request->header($headerKey) !== config('rest-presenter.auth.key')) {
            abort(403, 'Invalid API key');
        }

        return $next($request);
    }
}
