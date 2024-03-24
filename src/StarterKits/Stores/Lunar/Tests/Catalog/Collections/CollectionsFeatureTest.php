<?php

use Lunar\Models\Collection;
use Lunar\Models\CollectionGroup;

use XtendPackages\RESTPresenter\Data\Response\DefaultResponse;
use function Pest\Laravel\getJson;

$collectionsNb = 10;
beforeEach(function () use ($collectionsNb) {
    $this->collectionGroup = CollectionGroup::factory()
        ->state([
            'name' => 'Categories',
            'handle' => 'categories',
        ])
        ->has(Collection::factory()->count($collectionsNb))
        ->create();

    Collection::factory()->count($collectionsNb)->create();
    $this->collections = $this->collectionGroup->collections;
});

dataset('collections', function () use ($collectionsNb) {
    for ($i = 0; $i < $collectionsNb; $i++) {
        yield fn () => $this->collections->get($i);
    }
});

describe('Collections', function () {
    test('can get collection by id', function (Collection $collection) {
        $response = getJson(
            uri: route('api.v1.catalog:collections.show', [
                'collection' => $collection->id,
            ]),
        )->assertOk()->json();

        expect($response)
            ->toMatchArray(
                array: DefaultResponse::from($collection)->toArray(),
                message: 'Response data is in the expected format',
            );
    })->with('collections');

    test('can list all collections for a specific collection group', function () {
        $filters = [
            'collection_group_id' => $this->collectionGroup->id,
        ];
        $response = getJson(
            uri: route('api.v1.catalog:collections.index', ['filters' => $filters]),
        );

        expect($response->json('collections'))
            ->toHaveCount($this->collections->count());

        $response
            ->assertOk()
            ->assertJsonStructure(['collections']);
    });

    test('can list all collections', function () {
        $response = getJson(
            uri: route('api.v1.catalog:collections.index'),
        );

        expect($response->json('collections'))
            ->toHaveCount(Collection::count());

        $response
            ->assertOk()
            ->assertJsonStructure(['collections']);
    });
});
