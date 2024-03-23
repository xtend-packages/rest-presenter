<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Item;

use Illuminate\Http\Request;
use Lunar\Models\Product;
use XtendPackages\RESTPresenter\Contracts\Presentable;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Concerns\HasAvailability;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Concerns\HasPrices;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Concerns\HasUrls;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Concerns\HasVariants;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Data\Response\MediaData;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Data\Response\PriceData;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Data\Response\UrlData;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Detail\Data\Variant\ColorData;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Item\Data\ItemData;

class Item implements Presentable
{
    use HasUrls;
    use HasAvailability;
    use HasVariants;
    use HasPrices;

    public function __construct(
        private Request $request,
        private ?Product $model,
    ) {}

    public function transform(): ItemData
    {
        return ItemData::from([
            'id' => $this->model->id,
            'slug' => UrlData::from($this->getUrl()),
            'name' => $this->model->translateAttribute('name'),
            'availability' => $this->getAvailability(),
            'stock' => $this->model->stock,
            'colors' => ColorData::collect($this->getColors()),
            'price' => PriceData::from($this->getPrice()),
            'image' => MediaData::from($this->model->getFirstMedia('images')),
        ]);
    }
}
