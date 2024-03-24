<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Brands\Presenters\Brand;

use Illuminate\Http\Request;
use Lunar\Models\Url;
use Lunar\Models\Brand as BrandModel;
use XtendPackages\RESTPresenter\Concerns\InteractsWithPresenter;
use XtendPackages\RESTPresenter\Contracts\Presentable;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Data\Response\MediaData;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Brands\Presenters\Brand\Data\BrandData;

class Brand implements Presentable
{
    use InteractsWithPresenter;

    public function __construct(
        private Request $request,
        private ?BrandModel $model,
    ) {}

    public function transform(): BrandData
    {
        return BrandData::from([
            'id' => $this->model->id,
            'slug' => $this->getBrandSlug(),
            'name' => $this->model->name,
            'image' => $this->getImage(),
        ]);
    }

    protected function getBrandSlug(): string
    {
        return $this->model->urls->first(function (Url $url) {
            $matchesLocale = $url->language->code === app()->getLocale();

            return $matchesLocale || $url->language->code === config('app.fallback_locale');
        })?->slug;
    }

    protected function getImage(): ?MediaData
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
