<?php

declare(strict_types=1);

it('defines fillable attributes', function () {
    $user = new XtendPackages\RESTPresenter\Models\User();
    $this->assertEquals(['name', 'email', 'password'], $user->getFillable());
});

it('defines hidden attributes', function () {
    $user = new XtendPackages\RESTPresenter\Models\User();
    $this->assertEquals(['password', 'remember_token'], $user->getHidden());
});

it('returns correct factory instance', function () {
    $factory = XtendPackages\RESTPresenter\Models\User::newFactory();
    $this->assertInstanceOf(XtendPackages\RESTPresenter\Factories\UserFactory::class, $factory);
});

it('casts attributes correctly', function () {
    $user = new XtendPackages\RESTPresenter\Models\User();
    $casts = $user->getCasts();
    $this->assertArrayHasKey('email_verified_at', $casts);
    $this->assertEquals('datetime', $casts['email_verified_at']);
});
