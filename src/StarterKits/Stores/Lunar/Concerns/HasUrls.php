<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Concerns;

use Illuminate\Database\Eloquent\Model;
use Lunar\Models\Url;

trait HasUrls
{
    protected function getUrl(?Model $model = null): Url
    {
        $model ??= $this->model;

        $defaultUrl = $model->defaultUrl;
        if ($defaultUrl->language->code === app()->getLocale()) {
            return $defaultUrl;
        }

        return $model->urls->first(
            fn (Url $url) => $url->language->code === app()->getLocale(),
        );
    }
}
