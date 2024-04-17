<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Item;

use Illuminate\Http\Request;
use Lunar\Models\Product;
use Spatie\LaravelData\Data;
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

final class Item implements Presentable
{
    use HasAvailability;
    use HasPrices;
    use HasUrls;
    use HasVariants;

    public function __construct(
        protected Request $request,
        protected ?Product $model,
    ) {
    }

    public function transform(): ItemData|Data
    {
        return ItemData::from([
            'id' => $this->model->id,
            'slug' => UrlData::from($this->getUrl()),
            'name' => $this->model->translateAttribute('name'),
            'availability' => $this->getAvailability(),
            'colors' => ColorData::collect($this->getColors()),
            'price' => PriceData::from($this->getPrice()),
            'image' => $this->model->hasMedia('images') ? MediaData::from($this->model->getFirstMedia('images')) : null,
        ]);
    }
}
