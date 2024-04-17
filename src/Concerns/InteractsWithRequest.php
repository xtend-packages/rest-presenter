<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\Concerns;

use Illuminate\Support\Arr;

trait InteractsWithRequest
{
    /**
     * @param  string|int|null  $key
     */
    public function filterBy(mixed $key): mixed
    {
        return Arr::get($this->filtersFromRequest(), $key);
    }

    /**
     * @param  string|int  $key
     */
    public function hasFilter(mixed $key): bool
    {
        return array_key_exists(
            $key, method_exists($this, 'filtersFromRequest') ? $this->filtersFromRequest() : [],
        );
    }

    /**
     * @return array<string, string>
     */
    protected function filtersFromRequest(): array
    {
        $filters = request()->collect()->get('filters');
        if (is_array($filters)) {
            $filters = json_encode($filters);
        }

        if (! $filters) {
            return [];
        }

        $filters = type($filters)->asString();
        if (json_decode($filters) !== null) {
            $filters = json_decode($filters, true);
        }

        return type($filters ?? [])->asArray();
    }
}
