<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use XtendPackages\RESTPresenter\Support\Scalar;

Route::get('api-client/{endpointId}', Scalar\APiClient::class)
    ->name('api-client');
