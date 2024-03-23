<?php

use Lunar\Models\Collection;
use Lunar\Models\CollectionGroup;
use Lunar\Models\Language;
use Lunar\Models\Product;
use Lunar\Models\Url;
use function Pest\Laravel\get;

beforeEach(function () {
    Language::factory()->create(['code' => 'en']);
    $product = Product::factory()
        ->has(Url::factory()->state(['language_id' => 1]))
        ->create();

    $product->collections()->attach(
        Collection::factory()
            ->count(5)
            ->has(Url::factory()->state(['language_id' => 1]))
            ->create([
                'collection_group_id' => CollectionGroup::factory()->create([
                    'handle' => 'styles',
                ])->id,
            ]),
    );

    $this->product = $product;
});

describe('Detail Presenter', function () {
    test('transforms collection using Detail Presenter', function () {
        $response = get(
            uri: route('api.v1.catalog:products.show', [
                'product' => 1,
            ]),
            headers: [
                'X-AWR-PRESENTER' => 'Detail',
            ],
        );

        // @todo feature test [features, variants, colors, sizes, prices, images]

        $response->assertOk();
    });
});
