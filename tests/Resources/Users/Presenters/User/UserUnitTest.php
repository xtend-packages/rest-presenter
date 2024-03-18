<?php

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use XtendPackages\RESTPresenter\Models\User;
use XtendPackages\RESTPresenter\Resources\Users\Data\Response\UserData;
use XtendPackages\RESTPresenter\Resources\Users\Presenters;

beforeEach(function () {
    $this->request = mock(Request::class);
    $this->mockUser = mock(User::class)
        ->makePartial()
        ->forceFill([
            'id' => 1,
            'name' => fake()->name,
            'email' => fake()->email,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    $this->userPresenter = mock(
        Presenters\User::class,
        [$this->mockUser]
    );

    $this->userPresenter
        ->shouldReceive('transform')
        ->andReturn(UserData::from($this->mockUser));

    $this->response = $this->userPresenter->transform();
    $this->data = $this->response->toArray();
});

describe('User Presenter', function () {
    test('User::transform returns the correct data', function () {
        $this->response->validate($this->data);

        expect($this->response)->toBeInstanceOf(UserData::class)
            ->and($this->data)->toMatchArray([
                'id' => 1,
                'name' => $this->mockUser->name,
                'email' => $this->mockUser->email,
                'email_verified_at' => $this->mockUser->email_verified_at->format('Y-m-d\TH:i:s.u\Z'),
                'created_at' => $this->mockUser->created_at->format('Y-m-d\TH:i:s.u\Z'),
                'updated_at' => $this->mockUser->updated_at->format('Y-m-d\TH:i:s.u\Z'),
            ]);

    });

    test('User::transform data name validation fails', function () {
        $this->data['name'] = 'ok';
        $rules = $this->response->getValidationRules($this->data);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(__('validation.min.string', [
            'attribute' => 'name',
            'min' => getValidationRule('name', 'min', $rules),
        ]));
        $this->response->validate($this->data);
    });

    test('User::transform data email validation fails', function () {
        $this->data['email'] = 'invalid-email';

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(__('validation.email', ['attribute' => 'email']));
        $this->response->validate($this->data);
    });
});
