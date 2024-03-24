<?php

use Lunar\Models\Collection;
use Lunar\Models\CollectionGroup;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\CollectionGroups\Presenters\CategoryTree\Data\TreeData;
use function Pest\Laravel\getJson;

beforeEach(function () {
    $this->collectionGroup = CollectionGroup::factory()
        ->state([
            'name' => 'Categories',
            'handle' => 'categories',
        ])
        ->create();

    $this->collections = Collection::factory()
        ->count(3)
        ->state([
            'collection_group_id' => $this->collectionGroup->id,
        ])
        ->create();
});

describe('CategoryTree Presenter', function () {
    test('transforms collection using CategoryTree Presenter', function () {
        $response = getJson(
            uri: route('api.v1.catalog:collection-groups.show', ['collectionGroup' => 'categories']),
            headers: ['x-rest-presenter' => 'CategoryTree'],
        )->assertOk()->json();

        expect($response)
            ->toMatchArray(
                array: TreeData::from($response)->toArray(),
                message: 'Response data is in the expected format',
            );
    });
});
