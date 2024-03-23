<?php

use Illuminate\Support\Facades\Route;

Route::name('api.v1.')->prefix('api/v1')
    ->middleware(config('rest-presenter.api.middleware'))
    ->group(function () {
        Route::get('/', function () {
            return response()->json([
                'message' => 'Welcome to Lunar API',
            ]);
        });
    });
