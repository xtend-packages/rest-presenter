<?php

use Lunar\Models\Product;
use XtendPackages\RESTPresenter\Data\Response\DefaultResponse;
use function Pest\Laravel\getJson;

beforeEach(function () {
    $this->publishedProducts = Product::factory()
        ->state(['status' => 'published'])
        ->count(10)
        ->create();
    $this->draftProducts = Product::factory()
        ->state(['status' => 'draft'])
        ->count(5)
        ->create();
});

describe('Status Filter', function () {
    test('can list all products with published status', function () {
        $filters = [
            'status' => 'published',
        ];

        $response = getJson(
            uri: route('api.v1.catalog:products.index', ['filters' => $filters]),
        )->assertOk()->json();

        expect($response)
            ->toMatchArray(
                array: DefaultResponse::collect(
                    Product::where('status', $filters['status'])->get(),
                )->toArray(),
                message: 'Response data is in the expected format',
            )
            ->toHaveCount(
                $this->publishedProducts->count(),
            );
    });

    test('can list all products with draft status', function () {
        $filters = [
            'status' => 'draft',
        ];

        $response = getJson(
            uri: route('api.v1.catalog:products.index', ['filters' => $filters]),
        )->assertOk()->json();

        expect($response)
            ->toMatchArray(
                array: DefaultResponse::collect(
                    Product::where('status', $filters['status'])->get(),
                )->toArray(),
                message: 'Response data is in the expected format',
            )
            ->toHaveCount(
                $this->draftProducts->count(),
            );
    });
});
