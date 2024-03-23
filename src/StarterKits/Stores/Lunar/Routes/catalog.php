<?php

use Illuminate\Support\Facades\Route;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\CollectionGroups\CollectionGroupResourceController;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Collections\CollectionResourceController;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\ProductResourceController;

Route::prefix('products')->group(function () {
    Route::get('/', [ProductResourceController::class, 'index'])->name('products.index');
    Route::get('/{product}', [ProductResourceController::class, 'show'])->name('products.show');
});

Route::prefix('collections')->group(function () {
    Route::get('/', [CollectionResourceController::class, 'index'])->name('collections.index');
    Route::get('/{collection}', [CollectionResourceController::class, 'show'])->name('collections.show');
});

Route::prefix('collection-groups')->group(function () {
    Route::get('/', [CollectionGroupResourceController::class, 'index'])->name('collection-groups.index');
    Route::get('/{collectionGroup:handle}', [CollectionGroupResourceController::class, 'show'])->name('collection-groups.show');
});
