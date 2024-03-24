<?php

use Lunar\Models\Collection;
use Lunar\Models\CollectionGroup;
use Lunar\Models\Product;

use XtendPackages\RESTPresenter\Data\Response\DefaultResponse;
use function Pest\Laravel\getJson;

beforeEach(function () {
    $this->collectionGroup = CollectionGroup::factory()
        ->state([
            'name' => 'Styles',
            'handle' => 'styles',
        ])
        ->has(Collection::factory()->count(6))
        ->create();

    $this->collections = $this->collectionGroup->collections;

    $this->collections->each(function (Collection $collection) {
        $collection->products()->saveMany(
            Product::factory()
                ->state([
                    'status' => 'published',
                ])
                ->count(rand(1, 5))
                ->create()
        );
    });

    Product::factory()
        ->state(['status' => 'draft'])
        ->count(5)
        ->create();

    $this->products = Product::all();
});

dataset('products', function () {
    for ($i = 0; $i < 10; $i++) {
        yield fn () => $this->products->get($i);
    }
});

dataset('collections', function () {
    for ($i = 0; $i < 6; $i++) {
        yield fn () => $this->collections->get($i);
    }
});

describe('Products', function () {
    test('can show a product', function (Product $product) {
        $response = getJson(
            uri: route('api.v1.catalog:products.show', $product),
        )->assertOk()->json();

        expect($response)
            ->toMatchArray(
                array: DefaultResponse::from($product)->toArray(),
                message: 'Response data is in the expected format',
            );
    })->with('products');

    test('can list all products', function () {
        $response = getJson(
            uri: route('api.v1.catalog:products.index'),
        )->assertOk()->json();

        expect($response)
            ->toMatchArray(
                array: DefaultResponse::collect($this->products)->toArray(),
                message: 'Response data is in the expected format',
            )
            ->toHaveCount($this->products->count());
    });

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
                $this->products
                    ->where('status', $filters['status'])
                    ->count(),
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
                $this->products
                    ->where('status', $filters['status'])
                    ->count(),
            );
    });
});
