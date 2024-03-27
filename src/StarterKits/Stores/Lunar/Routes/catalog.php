<?php

use XtendPackages\RESTPresenter\Facades\XtendRoute;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Brands\BrandResourceController;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\CollectionGroups\CollectionGroupResourceController;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Collections\CollectionResourceController;
use XtendPackages\RESTPresenter\StarterKits\Stores\Lunar\Resources\Catalog\Products\ProductResourceController;

XtendRoute::resource('products', ProductResourceController::class);
XtendRoute::resource('collections', CollectionResourceController::class);
XtendRoute::resource('collection-groups', CollectionGroupResourceController::class);
XtendRoute::resource('brands', BrandResourceController::class);
