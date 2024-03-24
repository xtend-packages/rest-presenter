<?php

use Lunar\Models\Brand;
use XtendPackages\RESTPresenter\Data\Response\DefaultResponse;

use function Pest\Laravel\getJson;

$brandsNb = 10;
beforeEach(function () use ($brandsNb) {
    $this->brands = Brand::factory()->count($brandsNb)->create();
});

dataset('brands', function () use ($brandsNb) {
    for ($i = 0; $i < $brandsNb; $i++) {
        yield fn () => $this->brands->get($i);
    }
});

describe('Brands', function () {
    test('can show a brand', function (Brand $brand) {
        $response = getJson(
            uri: route('api.v1.catalog:brands.show', $brand),
        )->assertOk()->json();

        // Ignore attribute_data key until brand model cast is implemented
        unset($response['attributes']['attribute_data']);

        expect($response)
            ->toMatchArray(
                array: DefaultResponse::from($brand)->toArray(),
                message: 'Response data is in the expected format',
            );
    })->with('brands');

    test('can list all brands', function () {
        $response = getJson(
            uri: route('api.v1.catalog:brands.index'),
        )->assertOk()->json();

        expect($response)
            ->toMatchArray(
                array: DefaultResponse::collect(Brand::all())->toArray(),
                message: 'Response data is in the expected format',
            )
            ->toHaveCount(Brand::count());
    });
});
