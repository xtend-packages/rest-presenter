<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Detail;

use Illuminate\Http\Request;
use Lunar\Models\Product;
use XtendPackages\RESTPresenter\Concerns\InteractsWithPresenter;
use XtendPackages\RESTPresenter\Contracts\Presentable;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Concerns\HasAvailability;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Concerns\HasPrices;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Concerns\HasStyle;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Concerns\HasUrls;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Concerns\HasVariants;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Data\Response\MediaData;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Data\Response\PriceData;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Data\Response\UrlData;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Detail\Data\DetailData;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Detail\Data\StyleData;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Detail\Data\Variant\ColorData;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Detail\Data\Variant\SizeData;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Detail\Data\VariantData;

class Detail implements Presentable
{
    use HasAvailability;
    use HasPrices;
    use HasStyle;
    use HasUrls;
    use HasVariants;
    use InteractsWithPresenter;

    public function __construct(
        private Request $request,
        private ?Product $model,
    ) {}

    public function transform(): DetailData
    {
        return DetailData::from([
            'id' => $this->model->id,
            'url' => UrlData::from($this->getUrl()),
            'name' => $this->model->translateAttribute('name'),
            'description' => $this->model->translateAttribute('description'),
            'availability' => $this->getAvailability(),
            'variants' => VariantData::collect($this->model->variants),
            'colors' => ColorData::collect($this->getColors()),
            'sizes' => SizeData::collect($this->getSizes()),
            'prices' => PriceData::collect($this->model->prices),
            'images' => MediaData::collect($this->model->getMedia('images')),
        ]);
    }
}
