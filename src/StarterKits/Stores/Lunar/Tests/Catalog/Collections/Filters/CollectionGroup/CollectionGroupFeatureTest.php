<?php

use Lunar\Models\Collection;
use Lunar\Models\CollectionGroup;
use function Pest\Laravel\getJson;

beforeEach(function () {
    Collection::factory()->count(20)->create();
    $this->collectionGroup = CollectionGroup::factory()
        ->state([
            'name' => 'Styles',
            'handle' => 'styles',
        ])
        ->has(Collection::factory()->count(10))
        ->create();

    $this->collections = $this->collectionGroup->collections;
});

describe('CollectionGroup Filter', function () {
    test('can list all collections for a specific collection group', function () {
        $filters = [
            'collection_group_id' => $this->collectionGroup->id,
        ];

        $response = getJson(
            uri: route('api.v1.catalog:collections.index', ['filters' => $filters]),
        );

        $response
            ->assertOk()
            ->assertJsonCount($this->collections->count(), 'collections')
            ->assertJsonStructure(['collections']);
    });
});
