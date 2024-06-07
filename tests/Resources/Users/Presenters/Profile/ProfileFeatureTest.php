<?php

declare(strict_types=1);

use XtendPackages\RESTPresenter\Resources\Users\Data\Response\ProfileData;

use function Pest\Laravel\getJson;
use function XtendPackages\RESTPresenter\Support\Tests\authenticateApiUser;

beforeEach(function (): void {
    $this->user = authenticateApiUser();
});

describe('Profile Presenter', function (): void {
    test('transforms user using Profile Presenter', function (): void {
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
})->skip();
