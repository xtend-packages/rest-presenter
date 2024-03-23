<?php

use Lunar\Models\Product;
use function Pest\Laravel\get;

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

        $response = get(
            uri: route('api.v1.catalog:products.index', ['filters' => $filters]),
        );

        $response
            ->assertOk()
            ->assertJsonCount($this->publishedProducts->count(), 'products')
            ->assertJsonStructure(['products']);
    });

    test('can list all products with draft status', function () {
        $filters = [
            'status' => 'draft',
        ];

        $response = get(
            uri: route('api.v1.catalog:products.index', ['filters' => $filters]),
        );

        $response
            ->assertOk()
            ->assertJsonCount($this->draftProducts->count(), 'products')
            ->assertJsonStructure(['products']);
    });
});
