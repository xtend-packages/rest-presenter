<?php

declare(strict_types=1);

use Lunar\Models\Collection;
use Lunar\Models\CollectionGroup;
use XtendPackages\RESTPresenter\Data\Response\DefaultResponse;

use function Pest\Laravel\getJson;

beforeEach(function (): void {
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

describe('CollectionGroup Filter', function (): void {
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
});
