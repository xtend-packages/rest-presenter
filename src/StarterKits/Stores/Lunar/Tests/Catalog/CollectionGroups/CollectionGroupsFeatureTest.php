<?php

declare(strict_types=1);

use Lunar\Models\CollectionGroup;
use XtendPackages\RESTPresenter\Data\Response\DefaultResponse;

use function Pest\Laravel\getJson;

beforeEach(function (): void {
    $this->collectionGroups = CollectionGroup::factory()->count(5)->create();
});

dataset('collectionGroups', function () {
    for ($i = 0; $i < 5; $i++) {
        yield fn () => $this->collectionGroups->get($i);
    }
});

describe('CollectionGroups', function (): void {
    test('can show a collection', function (CollectionGroup $collectionGroup): void {
        $response = getJson(
            uri: route('api.v1.catalog:collection-groups.show', $collectionGroup),
        )->assertOk()->json();

        expect($response)
            ->toMatchArray(
                array: DefaultResponse::from($collectionGroup)->toArray(),
                message: 'Response data is in the expected format',
            );
    })->with('collectionGroups');

    test('can list all collectionGroups', function (): void {
        $response = getJson(
            uri: route('api.v1.catalog:collection-groups.index'),
        )->assertOk()->json();

        expect($response)
            ->toMatchArray(
                array: DefaultResponse::collect($this->collectionGroups)->toArray(),
                message: 'Response data is in the expected format',
            )
            ->toHaveCount(CollectionGroup::count());
    });
});
