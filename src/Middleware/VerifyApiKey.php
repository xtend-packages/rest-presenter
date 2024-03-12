<?php

namespace XtendPackages\RESTPresenter\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyApiKey
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->header(config('rest-presenter.auth.key_header')) !== config('rest-presenter.auth.key')) {
            abort(403, 'Invalid API key');
        }

        return $next($request);
    }
}
