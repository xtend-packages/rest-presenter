<?php

namespace XtendPackages\RESTPresenter\Resources\Users\Filters;

use Illuminate\Database\Eloquent\Builder;
use XtendPackages\RESTPresenter\Concerns\InteractsWithRequest;

class UserEmailVerified
{
    use InteractsWithRequest;

    public function handle(Builder $query, callable $next): Builder
    {
        if ($this->hasFilter('email_verified_at') && $this->filterBy('email_verified_at')) {
            $query->whereNotNull('email_verified_at');
        }

        return $next($query);
    }
}
