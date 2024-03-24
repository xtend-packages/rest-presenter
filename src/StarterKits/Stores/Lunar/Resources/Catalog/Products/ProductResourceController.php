<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lunar\Models\Product;
use XtendPackages\RESTPresenter\Resources\ResourceController;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Filters\Status;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Detail\Detail;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Presenters\Item\Item;

class ProductResourceController extends ResourceController
{
    protected static string $model = Product::class;

    public function index(Request $request): JsonResponse
    {
        return response()->json(['products' => $this->getModelQueryInstance()->get()]);
    }

    public function show(Request $request, Product $product): JsonResponse
    {
        return response()->json(
            data: $this->present($request, $product),
        );
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
