<?php

namespace XtendPackages\RESTPresenter\Concerns;

use Illuminate\Support\Arr;

trait InteractsWithRequest
{
    public function filterBy($key): mixed
    {
        return Arr::get($this->filtersFromRequest(), $key);
    }

    public function hasFilter($key): bool
    {
        return array_key_exists(
            $key, method_exists($this, 'filtersFromRequest') ? $this->filtersFromRequest() : [],
        );
    }

    protected function filtersFromRequest(): array
    {
        return request()->collect()->get('filters', []);
    }
}
