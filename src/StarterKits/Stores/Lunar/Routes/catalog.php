<?php

use Illuminate\Support\Facades\Route;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Brands\BrandResourceController;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\CollectionGroups\CollectionGroupResourceController;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Collections\CollectionResourceController;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\ProductResourceController;

Route::xtendResource('products', ProductResourceController::class);
Route::xtendResource('collections', CollectionResourceController::class);
Route::xtendResource('collection-groups', CollectionGroupResourceController::class);
Route::xtendResource('brands', BrandResourceController::class);
