<?php

declare(strict_types=1);

use XtendPackages\RESTPresenter\Data\Response\DefaultResponse;
use XtendPackages\RESTPresenter\Models\User;

use function Pest\Laravel\getJson;
use function XtendPackages\RESTPresenter\Support\Tests\authenticateApiUser;

beforeEach(function (): void {
    $this->user = User::factory()->create();
});

describe('ResourcePresenter', function (): void {
    test('can transform user resource returning default presenter', function (): void {
        authenticateApiUser($this->user);
        $response = getJson(
            uri: route('api.v1.users.show', $this->user),
        )->assertOk()->json();

        expect($response)
            ->toMatchArray(
                array: DefaultResponse::from($this->user)->toArray(),
                message: 'Response data is in the expected format',
            );
    });

    test('If not authenticated return unauthorized', function (): void {
        getJson(
            uri: route('api.v1.users.show', $this->user),
        )->assertUnauthorized();
    });
})->skip();
