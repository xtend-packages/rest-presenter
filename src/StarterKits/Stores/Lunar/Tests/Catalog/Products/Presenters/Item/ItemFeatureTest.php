<?php

use Lunar\Models\Language;
use Lunar\Models\Price;
use Lunar\Models\Product;
use Lunar\Models\ProductVariant;
use Lunar\Models\Url;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use function Pest\Laravel\get;

beforeEach(function () {
    Language::factory()->create(['code' => 'en']);
    $product = Product::factory()
        ->has(Url::factory()->state(['language_id' => 1]))
        ->create();

    Media::query()
        ->create([
            'collection_name' => 'images',
            'name' => 'image.jpg',
            'file_name' => 'image.jpg',
            'disk' => 'public',
            'size' => 1024,
            'manipulations' => [],
            'custom_properties' => [],
            'generated_conversions' => [],
            'responsive_images' => [],
            'model_id' => $product->id,
            'model_type' => Product::class,
        ]);

    ProductVariant::factory()
        ->count(30)
        ->has(Price::factory()->count(1))
        ->create([
            'product_id' => $product->id,
        ]);

    $this->product = $product;
});

describe('Item Presenter', function () {
    test('transforms collection using Item Presenter', function () {
        $response = get(
            uri: route('api.v1.catalog:products.show', [
                'product' => 1,
            ]),
            headers: [
                'X-AWR-PRESENTER' => 'Item',
            ],
        );

        // @todo feature test [colors]

        $response->assertOk();
    });
});
