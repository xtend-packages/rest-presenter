<?php

use XtendPackages\RESTPresenter\Models\User;
use XtendPackages\RESTPresenter\Resources\Users\Data\Response\UserData;
use function Pest\Laravel\getJson;

beforeEach(function () {
    $this->users = User::factory()->count(10)->create()->fresh();
    authenticateApiUser($this->users->first());
});

dataset('users', function () {
    for ($i = 0; $i < 10; $i++) {
        yield fn () => $this->users->get($i);
    }
});

describe('Users', function () {
    test('can show a user', function (User $user) {
        $response = getJson(
            uri: route('api.v1.users.show', $user),
            headers: ['x-rest-presenter' => 'user'],
        )->assertOk()->json();

        expect($response)
            ->toMatchArray(
                array: UserData::from($user)->toArray(),
                message: 'Response data is in the expected format',
            );
    })->with('users');

    test('can list all users', function () {
        $response = getJson(
            uri: route('api.v1.users.index'),
            headers: ['x-rest-presenter' => 'user'],
        )->assertOk()->json();

        expect($response)
            ->toMatchArray(
                array: UserData::collect($this->users)->toArray(),
                message: 'Response data is in the expected format',
            )
            ->toHaveCount($this->users->count());
    });
});
