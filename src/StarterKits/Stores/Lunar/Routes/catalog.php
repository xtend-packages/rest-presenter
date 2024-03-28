<?php

use XtendPackages\RESTPresenter\Facades\XtendRoute;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Brands\BrandResourceController;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\CollectionGroups\CollectionGroupResourceController;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Collections\CollectionResourceController;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\ProductResourceController;

/** @phpstan-ignore-next-line */
XtendRoute::resource('products', ProductResourceController::class);
/** @phpstan-ignore-next-line */
XtendRoute::resource('collections', CollectionResourceController::class);
/** @phpstan-ignore-next-line */
XtendRoute::resource('collection-groups', CollectionGroupResourceController::class);
/** @phpstan-ignore-next-line */
XtendRoute::resource('brands', BrandResourceController::class);
