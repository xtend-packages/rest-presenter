<?php

use Lunar\Models\Collection;
use Lunar\Models\CollectionGroup;
use Lunar\Models\Language;
use Lunar\Models\Url;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Collections\Presenters\Category\Data\CategoryData;

use function Pest\Laravel\getJson;

beforeEach(function (): void {
    Language::factory()->create(['code' => 'en']);
    $this->collectionGroup = CollectionGroup::factory()
        ->state([
            'name' => 'Categories',
            'handle' => 'categories',
        ])
        ->has(Collection::factory()->has(
            Url::factory()->state([
                'language_id' => 1,
            ])
        ))
        ->create();
});

describe('Category Presenter', function (): void {
    test('transforms collection using Category Presenter', function (): void {
        $response = getJson(
            uri: route('api.v1.catalog:collections.show', ['collection' => 1]),
            headers: ['x-rest-presenter' => 'Category'],
        )->assertOk()->json();

        expect($response)
            ->toMatchArray(
                array: CategoryData::from($response)->toArray(),
                message: 'Response data is in the expected format',
            );
    });
});
