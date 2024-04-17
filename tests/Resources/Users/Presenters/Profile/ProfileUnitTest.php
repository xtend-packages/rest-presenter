<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use XtendPackages\RESTPresenter\Models\User;
use XtendPackages\RESTPresenter\Resources\Users\Data\Response\ProfileData;
use XtendPackages\RESTPresenter\Resources\Users\Presenters;

beforeEach(function (): void {
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

    $this->profilePresenter = mock(
        Presenters\Profile::class,
        [$this->mockUser]
    );

    $this->profilePresenter
        ->shouldReceive('transform')
        ->andReturn(ProfileData::from($this->mockUser));

    $this->response = $this->profilePresenter->transform();
    $this->data = $this->response->toArray();
});

describe('Profile Presenter', function (): void {
    test('Profile::transform returns the correct data', function (): void {
        $this->response->validate($this->data);

        expect($this->response)->toBeInstanceOf(ProfileData::class)
            ->and($this->data)->toMatchArray([
                'id' => 1,
                'firstName' => ProfileData::firstName($this->mockUser->name),
                'lastName' => ProfileData::lastName($this->mockUser->name),
                'email' => $this->mockUser->email,
            ]);

    });

    test('Profile::transform data name validation fails', function (): void {
        $this->data['firstName'] = 'ok';
        $rules = $this->response->getValidationRules($this->data);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(__('validation.min.string', [
            'attribute' => 'first name',
            'min' => getValidationRule('firstName', 'min', $rules),
        ]));
        $this->response->validate($this->data);
    });

    test('Profile::transform data email validation fails', function (): void {
        $this->data['email'] = 'invalid-email';

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(__('validation.email', ['attribute' => 'email']));
        $this->response->validate($this->data);
    });
});
