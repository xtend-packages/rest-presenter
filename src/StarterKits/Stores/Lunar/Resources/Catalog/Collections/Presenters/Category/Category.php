<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Collections\Presenters\Category;

use Illuminate\Http\Request;
use Lunar\Models\Collection;
use Lunar\Models\Url;
use XtendPackages\RESTPresenter\Concerns\InteractsWithPresenter;
use XtendPackages\RESTPresenter\Contracts\Presentable;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Data\Response\MediaData;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Collections\Presenters\Category\Data\CategoryData;

class Category implements Presentable
{
    use InteractsWithPresenter;

    public function __construct(
        private Request $request,
        private ?Collection $model,
    ) {}

    public function transform(): CategoryData
    {
        return CategoryData::from([
            'id' => $this->model->id,
            'slug' => $this->getStyleSlug(),
            'name' => $this->model->translateAttribute('name'),
            'description' => $this->model->translateAttribute('description'),
            'banner' => $this->getBanner(),
        ]);
    }

    protected function getStyleSlug(): string
    {
        return $this->model->urls->first(function (Url $url) {
            $matchesLocale = $url->language->code === app()->getLocale();

            return $matchesLocale || $url->language->code === config('app.fallback_locale');
        })?->slug;
    }

    protected function getBanner(): ?MediaData
    {
        $mediaItem = $this->model->getFirstMedia('images');
        if (! $mediaItem) {
            return null;
        }

        return MediaData::from($mediaItem)->additional([
            'url' => $mediaItem->getUrl(),
        ]);
    }
}
