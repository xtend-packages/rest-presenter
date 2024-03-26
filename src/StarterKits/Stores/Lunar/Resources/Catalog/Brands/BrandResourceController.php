<?php

namespace XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Brands;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Lunar\Models\Brand;
use Spatie\LaravelData\Data;
use XtendPackages\RESTPresenter\Resources\ResourceController;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Brands\Presenters\Brand\Brand as BrandPresenter;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\Filters\Status;

class BrandResourceController extends ResourceController
{
    protected static string $model = Brand::class;

    public function index(Request $request): Collection
    {
        $brands = $this->getModelQueryInstance()->get();

        return $brands->map(
            fn (Brand $brand) => $this->present($request, $brand),
        );
    }

    public function show(Request $request, Brand $brand): Data
    {
        return $this->present($request, $brand);
    }

    public function filters(): array
    {
        return [

        ];
    }

    public function presenters(): array
    {
        return [
            'brand' => BrandPresenter::class,
        ];
    }
}
