<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Lunar\Models\Product;
use Spatie\LaravelData\Data;
use XtendPackages\RESTPresenter\Resources\ResourceController;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Filters\Brand;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Filters\Status;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Detail\Detail;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Item\Item;

final class ProductResourceController extends ResourceController
{
    protected static string $model = Product::class;

    public function index(Request $request): Collection
    {
        $products = $this->getModelQueryInstance()->get();

        return $products->map(
            fn ($product): \Spatie\LaravelData\Data => $this->present($request, $product),
        );
    }

    public function show(Request $request, Product $product): Data
    {
        return $this->present($request, $product);
    }

    public function filters(): array
    {
        return [
            'brand_id' => Brand::class,
            'status' => Status::class,
        ];
    }

    public function presenters(): array
    {
        return [
            'item' => Item::class,
            'detail' => Detail::class,
        ];
    }
}
