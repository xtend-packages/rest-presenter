<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Lunar\Models\Product;
use Spatie\LaravelData\Data;
use XtendPackages\RESTPresenter\Resources\ResourceController;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Filters\Status;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Detail\Detail;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Item\Item;

class ProductResourceController extends ResourceController
{
    protected static string $model = Product::class;

    public function index(Request $request): Collection
    {
        $products = $this->getModelQueryInstance()->get();

        return $products->map(
            fn (Product $product) => $this->present($request, $product),
        );
    }

    public function show(Request $request, Product $product): Data
    {
        return $this->present($request, $product);
    }

    public function filters(): array
    {
        return [
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
