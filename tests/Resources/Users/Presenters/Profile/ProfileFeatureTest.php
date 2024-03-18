<?php

use XtendPackages\RESTPresenter\Resources\Users\Data\Response\ProfileData;

use function Pest\Laravel\getJson;

beforeEach(function () {
    $this->user = authenticateApiUser();
});

describe('Profile Presenter', function () {
    test('transforms user using Profile Presenter', function () {
        $response = getJson(
            uri: route('api.v1.users.show', $this->user),
            headers: ['x-rest-presenter' => 'profile'],
        )->assertOk()->json();

        $this->assertNotSame($response, $this->user->toArray());

        expect($response)
            ->toMatchArray(
                array: ProfileData::from($this->user)->toArray(),
                message: 'Response data is in the expected format',
            );
    });
});
