<?php

use XtendPackages\RESTPresenter\Facades\XtendRoute;
use XtendPackages\RESTPresenter\StarterKits\Auth\Breeze\Http\Controllers\AuthenticatedSessionController;
use XtendPackages\RESTPresenter\StarterKits\Auth\Breeze\Http\Controllers\EmailVerificationNotificationController;
use XtendPackages\RESTPresenter\StarterKits\Auth\Breeze\Http\Controllers\NewPasswordController;
use XtendPackages\RESTPresenter\StarterKits\Auth\Breeze\Http\Controllers\PasswordResetLinkController;
use XtendPackages\RESTPresenter\StarterKits\Auth\Breeze\Http\Controllers\RegisteredUserController;
use XtendPackages\RESTPresenter\StarterKits\Auth\Breeze\Http\Controllers\VerifyEmailController;

/** @phpstan-ignore-next-line */
XtendRoute::auth(
    httpVerb: 'post',
    uri: '/register',
    controller: RegisteredUserController::class,
    name: 'register',
    middleware: ['guest'],
);

/** @phpstan-ignore-next-line */
XtendRoute::auth(
    httpVerb: 'post',
    uri: '/login',
    controller: AuthenticatedSessionController::class,
    name: 'login',
    middleware: ['guest'],
);

/** @phpstan-ignore-next-line */
XtendRoute::auth(
    httpVerb: 'post',
    uri: '/forgot-password',
    controller: PasswordResetLinkController::class,
    name: 'password.email',
    middleware: ['guest'],
);

/** @phpstan-ignore-next-line */
XtendRoute::auth(
    httpVerb: 'post',
    uri: '/reset-password',
    controller: NewPasswordController::class,
    name: 'password.store',
    middleware: ['guest'],
);

/** @phpstan-ignore-next-line */
XtendRoute::auth(
    httpVerb: 'get',
    uri: '/verify-email/{id}/{hash}',
    controller: VerifyEmailController::class,
    name: 'verification.verify',
    middleware: ['auth', 'signed', 'throttle:6,1'],
);

/** @phpstan-ignore-next-line */
XtendRoute::auth(
    httpVerb: 'post',
    uri: '/email/verification-notification',
    controller: EmailVerificationNotificationController::class,
    name: 'verification.send',
    middleware: ['auth', 'throttle:6,1'],
);

/** @phpstan-ignore-next-line */
XtendRoute::auth(
    httpVerb: 'post',
    uri: '/logout',
    controller: AuthenticatedSessionController::class,
    name: 'logout',
    middleware: ['auth'],
);
