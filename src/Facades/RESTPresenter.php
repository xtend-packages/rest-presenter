<?php

namespace XtendPackages\RESTPresenter\Facades;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Facade;
use XtendPackages\RESTPresenter\Base\RESTPresenter as BaseRESTPresenter;

/**
 * @see BaseRESTPresenter::register
 * @method static self register(Application $app)
 *
 * @see BaseRESTPresenter::starterKits
 * @method static self starterKits(array $starterKits)
 *
 * @see \XtendPackages\RESTPresenter\Base\RESTPresenter
 */
class RESTPresenter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'rest-presenter';
    }
}
