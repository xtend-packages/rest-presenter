<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Concerns;

use Illuminate\Database\Eloquent\Model;
use Lunar\Models\Url;

trait HasUrls
{
    protected function getUrl(?Model $model = null): Url
    {
        $model ??= $this->model;

        $defaultUrl = $model->defaultUrl ?? null;
        if ($defaultUrl->language->code === app()->getLocale()) {
            return $defaultUrl;
        }

        $urls = $model->urls ?? null;

        return $urls?->first(
            fn (Url $url): bool => $url->language->code === app()->getLocale(),
        );
    }
}
