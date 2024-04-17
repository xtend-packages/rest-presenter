<?php

declare(strict_types=1);

use XtendPackages\RESTPresenter\Resources\Users\Data\Response\UserData;

use function Pest\Laravel\getJson;

beforeEach(function (): void {
    $this->user = authenticateApiUser();
});

describe('User Presenter', function (): void {
    test('transforms user using User Presenter', function (): void {
        $response = getJson(
            uri: route('api.v1.users.show', $this->user),
            headers: ['x-rest-presenter' => 'user'],
        )->assertOk()->json();

        $this->assertNotSame($response, $this->user->toArray());

        expect($response)
            ->toMatchArray(
                array: UserData::from($this->user)->toArray(),
                message: 'Response data is in the expected format',
            );
    });
});
