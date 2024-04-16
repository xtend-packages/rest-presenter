<?php

namespace XtendPackages\RESTPresenter\Resources\Users\Filters;

use Illuminate\Database\Eloquent\Builder;
use XtendPackages\RESTPresenter\Concerns\InteractsWithRequest;

class UserEmailVerified
{
    use InteractsWithRequest;

    /**
     * @template TModelClass of \Illuminate\Database\Eloquent\Model
     *
     * @param  Builder<TModelClass>  $query
     * @param  callable(Builder<TModelClass>):Builder<TModelClass>  $next
     * @return Builder<TModelClass>
     */
    public function handle(Builder $query, callable $next): Builder
    {
        if ($this->hasFilter('email_verified_at') && $this->filterBy('email_verified_at')) {
            $query->whereNotNull('email_verified_at');
        }

        return $next($query);
    }
}
