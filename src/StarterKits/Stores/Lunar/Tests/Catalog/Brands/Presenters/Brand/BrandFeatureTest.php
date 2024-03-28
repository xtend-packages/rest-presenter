<?php

use Lunar\Models\Brand;
use Lunar\Models\Language;
use Lunar\Models\Url;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Brands\Presenters\Brand\Data\BrandData;

use function Pest\Laravel\getJson;

beforeEach(function () {
    Language::factory()->create(['code' => 'en']);
    $this->brand = Brand::factory()
        ->state(['name' => 'New Brand'])
        ->has(Url::factory()->state(['language_id' => 1]))
        ->create();
});

describe('Brand Presenter', function () {
    test('transforms brand using Brand Presenter', function () {
        $response = getJson(
            uri: route('api.v1.catalog:brands.show', $this->brand),
            headers: ['x-rest-presenter' => 'Brand'],
        )->assertOk()->json();

        expect($response)
            ->toMatchArray(
                array: BrandData::from($response)->toArray(),
                message: 'Response data is in the expected format',
            );
    });
});
