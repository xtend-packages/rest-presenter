<?php

use Lunar\Models\Brand;
use Lunar\Models\Product;
use Lunar\Models\Url;
use XtendPackages\RESTPresenter\Data\Response\DefaultResponse;
use function Pest\Laravel\getJson;

beforeEach(function () {
    Product::factory()->count(10)->create();

    $this->brand = Brand::factory()
        ->state(['name' => 'New Brand'])
        ->has(Url::factory()->state([
            'language_id' => 1,
            'slug' => 'new-brand',
        ]))
        ->create();
    $this->newBrandProducts = Product::factory()
        ->state(['brand_id' => $this->brand->id])
        ->count(20)
        ->create();
});

describe('Brand Filter', function () {
    test('can list all products by brand', function () {
        $filters = [
            'brand_id' => $this->brand->id,
        ];

        $response = getJson(
            uri: route('api.v1.catalog:products.index', ['filters' => $filters]),
        )->assertOk()->json();

        expect($response)
            ->toMatchArray(
                array: DefaultResponse::collect(
                    Product::where('brand_id', $this->brand->id)->get(),
                )->toArray(),
                message: 'Response data is in the expected format',
            )
            ->toHaveCount(
                $this->newBrandProducts->count(),
            );
    });
});
