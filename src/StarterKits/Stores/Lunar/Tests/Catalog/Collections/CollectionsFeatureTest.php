<?php

declare(strict_types=1);

use Lunar\Models\Collection;
use Lunar\Models\CollectionGroup;
use XtendPackages\RESTPresenter\Data\Response\DefaultResponse;

use function Pest\Laravel\getJson;

$collectionsNb = 10;
beforeEach(function () use ($collectionsNb): void {
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

describe('Collections', function (): void {
    test('can show a collection', function (Collection $collection): void {
        $response = getJson(
            uri: route('api.v1.catalog:collections.show', $collection),
        )->assertOk()->json();

        expect($response)
            ->toMatchArray(
                array: DefaultResponse::from($collection)->toArray(),
                message: 'Response data is in the expected format',
            );
    })->with('collections');

    test('can list all collections for a specific collection group', function (): void {
        $filters = [
            'collection_group_id' => $this->collectionGroup->id,
        ];

        $response = getJson(
            uri: route('api.v1.catalog:collections.index', ['filters' => $filters]),
        )->assertOk()->json();

        expect($response)
            ->toMatchArray(
                array: DefaultResponse::collect($this->collections)->toArray(),
                message: 'Response data is in the expected format',
            )
            ->toHaveCount($this->collections->count());
    });

    test('can list all collections', function (): void {
        $response = getJson(
            uri: route('api.v1.catalog:collections.index'),
        )->assertOk()->json();

        expect($response)
            ->toMatchArray(
                array: DefaultResponse::collect($this->collections)->toArray(),
                message: 'Response data is in the expected format',
            )
            ->toHaveCount(Collection::count());
    });
});
