<?php

use Lunar\Models\CollectionGroup;

use XtendPackages\RESTPresenter\Data\Response\DefaultResponse;
use function Pest\Laravel\getJson;

beforeEach(function () {
    $this->collectionGroups = CollectionGroup::factory()->count(5)->create();
});

dataset('collectionGroups', function () {
    for ($i = 0; $i < 5; $i++) {
        yield fn () => $this->collectionGroups->get($i);
    }
});

describe('CollectionGroups', function () {
    test('can get collection by its handle', function (CollectionGroup $collectionGroup) {
        $response = getJson(
            uri: route('api.v1.catalog:collection-groups.show', [
                'collectionGroup' => $collectionGroup->handle,
            ]),
        )->assertOk()->json();

        expect($response)
            ->toMatchArray(
                array: DefaultResponse::from($collectionGroup)->toArray(),
                message: 'Response data is in the expected format',
            );
    })->with('collectionGroups');

    test('can list all collectionGroups', function () {
        $response = getJson(
            uri: route('api.v1.catalog:collection-groups.index'),
        );

        expect($response->json('collectionGroups'))
            ->toHaveCount($this->collectionGroups->count());

        $response
            ->assertOk()
            ->assertJsonStructure(['collectionGroups']);
    });
});
