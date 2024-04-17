<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::name('api.v1.')->prefix('api/v1')
    ->middleware(config('rest-presenter.api.middleware'))
    ->group(function (): void {
        Route::name('catalog:')->prefix('catalog')->group(__DIR__.'/catalog.php');
    });
